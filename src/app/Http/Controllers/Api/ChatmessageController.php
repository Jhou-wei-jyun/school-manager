<?php

namespace App\Http\Controllers\Api;

use App\Jobs\FCMNotification;
use App\Parents;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\API\ApiHelper;
use App\Chat_message;
use App\Http\Resources\Api\GetList;
use Carbon\Carbon;

class ChatmessageController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        // $userlist = User::where('school_id',$request->school_id)
        //             ->where('position_id', '!=', 10)
        //             ->select('name','avatar','id')
        //             ->get();
        $parentlist = Parents::where('school_id', $request->school_id)
            ->select('name', 'parent_id')
            ->get();
        // $list = array_sort_recursive(array_collapse([$userlist, $parentlist]));

        return ['parent' => $parentlist];
    }
    public function messagesave(Request $request)
    {
        $r_message = $request->input('msg');
        $t_message = $request->input('msg');

        // status 1. 未讀 2. 下載至本地端 ,3. 已讀
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone);
        $t_message = json_decode($t_message, true);
        $message = new Chat_message;
        $message->date = $today->format('Y-m-d H:i:s.u');
        $message->from = $t_message['from'];
        $message->to = "";
        $message->status = '1';
        $message->message = $t_message["message"];
        $message->school_id = ''; // 學校
        $message->parent_id = $t_message["parentid"]; //家長
        $message->user_id = $t_message["userid"]; //小孩
        $message->identity = $t_message["identity"]; // 身份

        $message->save();

        $type_message = 'fromChat';
        $type_sound = 'spaceship_alarm.mp3';
        if ($t_message['identity'] == 'Parent') {
            $teacher_id  = User::find($t_message["userid"])->department->supervisor_id;
            $user = User::where('id', $teacher_id)->first();
            $token = $user->device_token;
            $name = $user->name;
        } else {
            $parent = Parents::where('parent_id', $t_message["parentid"])->first();
            $token = $parent->device_token;
            $name = $parent->name;
        }
        $data = [
            'id' => 'ChatMessage',
            'date' => $today->format('Y-m-d H:i:s.u'),
            'title' => $name,
            'message' => $t_message["message"],
            'type' => $type_message,
            'token' => $token,
            'sound' => $type_sound,
            // 'system' => 'ios',
        ];

        $job = (new FCMNotification($data))->onConnection('redis_high');
        $this->dispatch($job);
        //broadcast(new Message($message));
        return $this->succeed($r_message, 200);
    }
    public function getmessage(Request $request)
    {
        // if ($request->user_id != null) {
        //     $message = Chat_message::where('sender',$request->sender)
        //                 ->where('sender_id',$request->sender_id)
        //                 ->where('user_id', $request->user_id)
        //                 // ->orderBy('created_at','desc')
        //                 ->select(['message','status','created_at'])
        //                 // ->take(10)
        //                 ->get();
        // }
        $message = Chat_message::where('user_id', '=', $request->userid)
            ->where('parent_id', '=', $request->parentid)
            ->where('identity', '=', $request->identity)
            ->where('status', '=', '1')
            ->selectRaw("SUBSTRING(DATE_FORMAT(date,'%Y-%m-%d %T.%f'),1,23) as date,user_id,message,identity,parent_id,chat_messages.`to`,chat_messages.`from`")
            ->get();

        return $message;
    }
    public function messageupdate(Request $request)
    {

        $parents = Chat_message::where('date', '=', $request->date)
            ->where("parent_id", '=', $request->parentid)
            ->where("user_id", '=', $request->userid)
            ->update(
                array(
                    'status' => "2",
                )
            );

        //$parents->device_token = $request->devicetoken;

        return $this->succeed($parents, 200);
    }
    // public function private(Request $request) {

    //     if ($request->user_id != null) {
    //         $user = User::where('id',$request->user_id)
    //                     ->get();
    //     }
    //     if ($request->parent_id != null) {
    //         $user = Parents::where('parent_id',$request->parent_id)
    //                     ->get();
    //     }
    //     broadcast(new Message($user));
    //     // broadcast(new PrivateEvent());
    //     return 'private';
    // }
    public function testsend(Request $request)
    {

        // $timezone = config('services.time_zone');
        // $today = Carbon::now($timezone)->format('Y-m-d');
        $message = new Chat_message;
        // $message->date = $today;
        $message->sender = $request->sender;
        $message->sender_id = $request->sender_id;
        $message->to = $request->to;
        $message->message = $request->message;
        $message->status = $request->status;
        $message->school_id = $request->school_id;
        $message->parent_id = $request->parent_id;
        // $message->user_id = $request->user_id;
        $message->save();

        broadcast(new Message($message));
        return $this->succeed('', 200);
    }
    public function unread_check(Request $request)
    {
        $teacher_id = $request->teacher_id;
        $parent_id = $request->parent_id;
        $student_id = $request->student_id; //array
        if ($teacher_id) {
            $unread_list = Chat_message::whereIn('user_id', $student_id)
                ->select('status', 'user_id')->get();
        }
        if ($parent_id) {
            $unread_list = Chat_message::whereIn('user_id', $student_id)
                ->select('status', 'user_id')->get();
        }
        $unread_list = $unread_list->filter(function ($item) {
            return $item->status == 1;
        });
        $grouped = $unread_list->groupBy('user_id');
        $grouped->toArray();
        return response()->json($grouped);
    }
}
