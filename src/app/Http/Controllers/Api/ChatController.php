<?php

namespace App\Http\Controllers\Api;

use App\Jobs\FCMNotification;
use App\Events\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\API\ApiHelper;
use Carbon\Carbon;

use App\Parents;
use App\User;
use App\spu_relationship;

use App\Chat_message;
use App\Http\Resources\Api\GetList;
use App\Machine;

use Illuminate\Support\Facades\Validator;

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use GuzzleHttp\Client;

class ChatController extends Controller
{
    use ApiHelper;
    public function readMessage(Request $request)
    {
        $teacher_id = $request->teacher_id;
        $parent_id = $request->parent_id;
        $user_id = $request->user_id;

        if ($teacher_id) { //老師帳號
            //從user_id取得parent_id
            $parent_id = spu_relationship::where('user_id', $user_id)->first()->parent_id;
            //read_change
            Chat_message::where('user_id', $user_id)
                ->where('teacher_id', $teacher_id)->where('parent_id', $parent_id)
                ->where('status', 0) // status 0. 未讀 1. 已讀
                ->where('identity', 'Parent') //來自家長的未讀的全部已讀
                ->update([
                    'status' => 1
                ]);
        } else if ($parent_id) { //家長帳號
            //從user_id取得teacher_id
            $teacher_id = User::find($user_id)->department->supervisor_id;
            Chat_message::where('user_id', $user_id)
                ->where('teacher_id', $teacher_id)->where('parent_id', $parent_id)
                ->where('status', 0) // status 0. 未讀 1. 已讀
                ->where('identity', 'Teacher') //來自老師的未讀的全部已讀
                ->update([
                    'status' => 1
                ]);
        }
        return $this->succeed('', 200);
    }
    public function getMessage(Request $request)
    {
        //teacher . parent 擇一
        $teacher_id = $request->teacher_id;
        $parent_id = $request->parent_id;
        $user_id = $request->user_id;

        if ($teacher_id) { //老師帳號
            //從user_id取得parent_id
            $parent_id = spu_relationship::where('user_id', $user_id)->first()->parent_id;
            //read_change
            Chat_message::where('user_id', $user_id)
                ->where('teacher_id', $teacher_id)->where('parent_id', $parent_id)
                ->where('status', 0) // status 0. 未讀 1. 已讀
                ->where('identity', 'Parent') //來自家長的未讀的全部已讀
                ->update([
                    'status' => 1
                ]);
            $messages = Chat_message::where('user_id', $user_id)
                ->where('teacher_id', $teacher_id)->where('parent_id', $parent_id)->orderBy('id', 'desc')
                ->take(100)
                ->get()->toArray();
        } else if ($parent_id) { //家長帳號
            //從user_id取得teacher_id
            $teacher_id = User::find($user_id)->department->supervisor_id;
            $read_change = Chat_message::where('user_id', $user_id)
                ->where('teacher_id', $teacher_id)->where('parent_id', $parent_id)
                ->where('status', 0) // status 0. 未讀 1. 已讀
                ->where('identity', 'Teacher') //來自老師的未讀的全部已讀
                ->update([
                    'status' => 1
                ]);
            $messages = Chat_message::where('user_id', $user_id)
                ->where('teacher_id', $teacher_id)->where('parent_id', $parent_id)->orderBy('id', 'desc')
                ->take(100)
                ->get()->toArray();
        }
        return array_reverse($messages);
    }
    public function saveMessage($teacher_id, $parent_id, $identity, $message, $user_id)
    {
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone);
        $newMessage = new Chat_message;
        $newMessage->teacher_id = $teacher_id;
        $newMessage->parent_id = $parent_id;
        $newMessage->identity = $identity; //Teacher or Parent
        $newMessage->message = $message;
        $newMessage->status = 0; //未讀
        $newMessage->user_id = $user_id;
        $newMessage->save();

        return $newMessage;
    }
    public function Notification($teacher_id, $parent_id, $identity, $message, $student_id)
    {

        $type_message = 'fromChat';
        $type_sound = 'spaceship_alarm.mp3';
        $teacher = User::find($teacher_id);
        $parent = Parents::find($parent_id);
        if ($identity == 'Teacher') {
            $token = $parent->device_token;
            $name = $teacher->profile->name;
        }
        if ($identity == 'Parent') {
            $token = $teacher->device_token;
            $name = $parent->name;
        }
        $data = [
            'id' => 'ChatMessage',
            'title' => $name,
            'message' => $message,
            'type' => $type_message,
            'token' => $token,
            'sound' => $type_sound,
            'student_id' => $student_id,
        ];

        return $data;
    }
    public function send(Request $request)
    {
        //teacher . parent 擇一
        $teacher_id = (int) $request->teacher_id;
        $parent_id = (int) $request->parent_id;
        $user_id = (int) $request->user_id;
        $message = $request->message;
        $isShortCutFromParent =  $request->isShortCutFromParent;
        if ($teacher_id) {
            $identity = 'Teacher';
            //從user_id取得parent_id
            $parent_id = spu_relationship::where('user_id', $user_id)->first()->parent_id;
            $newMessage = $this->saveMessage($teacher_id, $parent_id, $identity, $message, $user_id);
            broadcast(new Message($newMessage));
            // event(new Message($newMessage));
            $data = $this->Notification($teacher_id, $parent_id, $identity, $message, $user_id);
            $job = (new FCMNotification($data))->onConnection('redis_high');
            $this->dispatch($job);
        } else if ($parent_id) {
            $identity = 'Parent';
            //從user_id取得teacher_id
            $user = User::find($user_id);
            $parent = Parents::find($parent_id);
            $teacher_id = $user->department->supervisor_id;
            $newMessage = $this->saveMessage($teacher_id, $parent_id, $identity, $message, $user_id);
            broadcast(new Message($newMessage));
            // event(new Message($newMessage));
            $data = $this->Notification($teacher_id, $parent_id, $identity, $message, $user_id);
            $job = (new FCMNotification($data))->onConnection('redis_high');
            $this->dispatch($job);
            $isShortCutFromParent = filter_var($isShortCutFromParent, FILTER_VALIDATE_BOOLEAN);
            if ($isShortCutFromParent) {
                $this->textSpeechToDevice($user, $parent, $message);
            }
        }
        return $this->succeed($newMessage, 200);
    }
    public function unreadCheck(Request $request)
    {
        $teacher_id = $request->teacher_id;
        $parent_id = $request->parent_id;
        $unread_lists = collect([]);
        if ($teacher_id) {
            $student_list = User::where('id', $teacher_id)->with('departments.students')->first();
            $student_list = $student_list->departments->map(function ($department) {
                return $department->students->isEmpty() ? null : $department->students->pluck('id');
            })->collapse(); //array
            foreach ($student_list as $student_id) {
                $relationship = spu_relationship::where('user_id', $student_id)->first();
                $unread_list = Chat_message::where('user_id', $student_id)
                    ->where('status', 0)->where('identity', 'Parent') //來自家長的未讀的全部顯示
                    ->where('teacher_id', $teacher_id)
                    ->where('parent_id', $relationship->parent_id)
                    ->select('status', 'user_id')->get();
                $unread_lists = $unread_lists->merge($unread_list);
            }
        } else if ($parent_id) {
            $student_list = spu_relationship::where('parent_id', $parent_id)->get();
            $student_list = $student_list->map(function ($item) {
                return $item->user_id;
            });
            foreach ($student_list as $student_id) {
                $relationship = User::find($student_id)->department;
                $unread_list = Chat_message::where('user_id', $student_id)
                    ->where('status', 0)->where('identity', 'Teacher') //來自家長的未讀的全部顯示
                    ->where('parent_id', $parent_id)
                    ->where('teacher_id', $relationship->supervisor_id)
                    ->select('status', 'user_id')->get();
                $unread_lists = $unread_lists->merge($unread_list);
            }
        }
        $grouped = $unread_lists->groupBy('user_id');
        return $grouped;
    }
    // public function audioSent(Request $request)
    // {
    //     $user_id = (int) $request->user_id;
    //     $parent_id = (int) $request->parent_id;
    //     $message = $request->message;
    //     $validator = Validator::make(
    //         [
    //             'user_id' => $user_id,
    //             'parent_id' => $parent_id,
    //         ],
    //         [
    //             'user_id' => 'required',
    //             'parent_id' => 'required',
    //         ]
    //     );
    //     if ($validator->fails()) {
    //         return $this->errors('fails', $validator->errors(), 401);
    //     }
    //     $type_message = 'audioMessage';
    //     $type_sound = 'spaceship_alarm.mp3';
    //     $user = User::find($user_id);
    //     $parent = Parents::find($parent_id);
    //     $tokens = Machine::where('school_id', $parent->school_id)->get()->pluck('device_token');
    //     $name = $parent->name;
    //     $this->textSpeechToDevice($user, $parent, $message);
    //     // $data = [
    //     //     'id' => 'ChatMessage',
    //     //     'title' => $name,
    //     //     'message' => $message,
    //     //     'type' => $type_message,
    //     //     'token' => $tokens,
    //     //     'sound' => $type_sound,
    //     // ];

    //     // $job = (new FCMNotification($data));
    //     // $this->dispatch($job);

    //     return $this->succeed('', 200);
    // }
    // public function textToSound($text, $file_name)
    // {
    //     // $text = $request->text;
    //     $timezone = config('services.time_zone');
    //     $current = Carbon::now($timezone)->timestamp;
    //     // create client object
    //     $client = new TextToSpeechClient([
    //         'credentials' => json_decode(file_get_contents(config_path('texttospeech.json')), true)
    //     ]);

    //     $input_text = (new SynthesisInput())
    //         ->setText($text);

    //     // note: the voice can also be specified by name
    //     // names of voices can be retrieved with $client->listVoices()
    //     $voice = (new VoiceSelectionParams())
    //         ->setLanguageCode('zh-TW')
    //         ->setSsmlGender(SsmlVoiceGender::FEMALE);

    //     $audioConfig = (new AudioConfig())
    //         ->setAudioEncoding(AudioEncoding::MP3);

    //     $response = $client->synthesizeSpeech($input_text, $voice, $audioConfig);
    //     $audioContent = $response->getAudioContent();

    //     $path = 'audio/';
    //     if (!is_dir($path)) {
    //         $flag = mkdir($path, 0777, true);
    //     }
    //     $path_name = $path . $file_name . '-' . $current . '.mp3';
    //     file_put_contents($path_name, $audioContent);
    //     $this->speechToDevice($path_name);
    //     $client->close();
    //     return $path . $file_name . '.mp3';
    // }
    public function textSpeechToDevice($user, $parent, $message)
    {
        try {
            $base_uri = 'https://api.pushy.me/push?api_key=';
            $api_key = '65139183b78cf94017209edd9f7d2907bb3cd20708c7edc1c3a3e691532dd180';

            $machines = Machine::where('school_id', $user->school_id)->get();

            $client = new Client(['base_uri' => $base_uri . $api_key]);
            foreach ($machines as $machine) {
                $client->post('', [
                    'json' => [
                        "to" =>  $machine->device_token,
                        "data" => [
                            "message" => $message,
                        ],
                        "notification" => [
                            "body" => "Hello World \u270c",
                            "badge" => 1,
                        ]
                    ]
                ]);
            }
        } catch (\Exception $e) {
        }
    }
}
