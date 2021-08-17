<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use App\API\ApiHelper;
use App\API\ApiVerificationLog;
use App\By_pass;
use App\Leave;
use App\Notify;
use App\NotUserVerification;
use App\Parents;
use App\Qrcode;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Jobs\FCMNotification;
use App\Temperature;
use Patchwork\Utf8;

class QrcodeController extends Controller
{
    use ApiHelper;
    use ApiVerificationLog;

    public function inSchoolCheck(Request $request)
    {
        $parent_id = $request->parent_id;
        $parent = Parents::find($parent_id);
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        if ($parent_id) {
            $user_id_arr = $parent->spu_relationship->pluck('user_id');
            // 確認學生是否有到校
            $record_user_list = Temperature::whereIn('user_id', $user_id_arr)
                ->where('record_time', 'like', '%' . $today . '%')
                ->get()->pluck('user_id')->unique();
            $leave_list = Leave::whereIn('user_id', $record_user_list)
                ->where('created_at', 'like', '%' . $today . '%')
                ->get();
            if ($leave_list->isEmpty()) {
                foreach ($record_user_list as $user_id) {
                    $leave_add = new Leave([
                        'phone' => $parent->phone,
                        'name' => $parent->name,
                        'title' => $parent->title,
                        'status' => 0,
                    ]);
                    $user = User::find($user_id);
                    $user->leaves()->save($leave_add);
                }
                $leave_list = Leave::whereIn('user_id', $record_user_list)
                    ->where('created_at', 'like', '%' . $today . '%')
                    ->where('status', false)
                    ->pluck('user_id');
                return $this->succeed($leave_list, 200);
            } else {
                $leave_list = Leave::whereIn('user_id', $record_user_list)
                    ->where('created_at', 'like', '%' . $today . '%')
                    ->where('status', false)
                    ->pluck('user_id');
                return $this->succeed($leave_list, 200);
            }
        }
    }
    public function createURL(Request $request)
    {
        $user_id_arr = json_decode($request->user_id, true);
        $parent_id = $request->parent_id;
        $otherName = $request->otherName;
        $otherPhone = $request->otherPhone;
        $otherTitle = $request->otherTitle;
        $validator = Validator::make(
            [
                'parent_id' => $parent_id,
            ],
            [
                'parent_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('parent_id was not found', $validator->errors(), 401);
        }
        if ($otherPhone) {
            $validator = Validator::make(
                [
                    'otherPhone' => $otherPhone,
                ],
                [
                    'otherPhone' => 'min:10|max:10',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('phone was not correct', $validator->errors(), 401);
            }
        }

        $timezone = config('services.time_zone');
        $create_time = Carbon::now($timezone);

        $parent = Parents::find($parent_id);
        $parmas = json_encode([
            'user_id_arr' => $user_id_arr,
            'parent_phone' => $parent->phone,
            'parent_id' => $parent->parent_id,
            'parent_name' => $parent->name,
            'parent_title' => $parent->title,
            'create_time' => $create_time,
            'other_people' => [
                'name' => $otherName,
                'phone' => $otherPhone,
                'title' => $otherTitle,
            ]
        ]);
        // return $parmas;
        $token = Str::random(30);
        // $encrypted_token = Crypt::encryptString($token);
        // $url = 'http://' . request()->getHttpHost() . '/api/qrcode/verify/' . $encrypted_token;
        $url = 'https://' . request()->getHost() . '/api/qrcode/verify/' . $token;

        //驗證Qrcode存在
        $qrcode = Qrcode::where('parent_id', $parent_id)->first();
        if ($qrcode) { //qrcode存在

            $qrcode->update([
                'token' =>  $token,
                'start_time' =>  $create_time,
                'parmas' =>  $parmas,
            ]);
            return $this->succeed($url, 200);
        } else {
            $qrcode_add = new Qrcode([
                'parent_id' => $parent_id,
                'token' =>  $token,
                'start_time' =>  $create_time,
                'parmas' =>  $parmas,
            ]);
            $qrcode_add->save();
            return $this->succeed($url, 200);
        }
    }
    public function qrcodeCheck($token, $token_params)
    {
        //檢查是否有新Qrcode生成
        $qrcode = Qrcode::where('parent_id', $token_params['parent_id'])->first();
        $timezone = config('services.time_zone');
        $scan_time = Carbon::now($timezone);
        $diff_time = carbon::parse($scan_time)->diffInHours(Carbon::parse($qrcode->start_time), true);
        if ($token !== $qrcode->token || $diff_time >= 23) {
            return false;
        }
        return true;
    }
    public function Verify(Request $request)
    {
        // $token = str_replace('1IlOc7', '', $request->token);
        // $decrypted_token = Crypt::decryptString($token);
        $token = $request->token;
        $auth_token = $request->pickup_authentication;
        if ($auth_token !== 'ilolly') {
            return abort(404);
        }
        $qrcode = Qrcode::where('token', $token)->first();
        //Qrcode存在
        if ($qrcode) {
            $token_params = json_decode($qrcode->parmas, true);
        } else {
            return $this->error('接送申請已註銷', 503);
        }
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        if (!$this->qrcodeCheck($token, $token_params)) {
            return $this->error('Qrcode已失效', 504);
        }
        $parent_check = Parents::where('parent_id', $token_params['parent_id'])
            ->where('phone', $token_params['parent_phone'])
            ->first();
        $by_pass = By_pass::all()->pluck('phone')->toArray();

        if ($parent_check) {
            if ($token_params['other_people']['phone']) {
                if (in_array($token_params['other_people']['phone'], $by_pass)) {
                    //電話號碼在白名單內
                    $send_status = $this->setVerification($token_params['other_people']['phone'], true);
                } else {
                    $send_status = $this->setVerification($token_params['other_people']['phone'], false);
                }
                if ($send_status === 'limit') {
                    return $this->error('超過發送上限，請等待5分鐘後重新發送', 505);
                } else if ($send_status) {
                    $exist_verification =  NotUserVerification::where('phone', $token_params['other_people']['phone'])->first();
                    $token_params['verification'] = $exist_verification->verification_num;
                    $student = User::whereIn('id', $token_params['user_id_arr'])->with('profile')->get();
                    $token_params['student'] = $student->map(function ($item) {
                        $collection = collect([
                            'user_id' => $item->id,
                            'user_name' => $item->profile->name,
                        ]);
                        return $collection;
                    });
                    return $this->succeed($token_params, 200);
                } else {
                    return $this->error('簡訊發送失敗', 506);
                }
            }
            try {
                $inSchoolCheck = Leave::whereIn('user_id', $token_params['user_id_arr'])
                    ->where('created_at', 'like', '%' . $today . '%')
                    ->where('status', false)
                    ->get();
                if (!$inSchoolCheck->isEmpty()) {
                    $this->recordLeaveTable($token_params, 1, $today); //0為尚未接送 1為已接送
                    $token_params['leave_result'] = true;
                    return $this->succeed($token_params, 200); //成功接走小孩
                } else {
                    $token_params['leave_result'] = false;
                    return $this->succeed($token_params, 200); //所有小孩已被接走
                }
            } catch (\Exception $e) {
                return $this->error($e, 400);
            }
        }
        return $this->error('parent_id is not exist', 501);
    }
    // public function setVerification($phone)
    // {

    //     $send_sms = false;
    //     $timezone = config('services.time_zone');
    //     $now = Carbon::now($timezone)->format('Y-m-d H:i:s');
    //     $Code = "";
    //     // 產生隨機認證碼
    //     for ($i = 0; $i < 4; $i++) {
    //         $fontcontent = rand(0, 9);
    //         $Code .= strval($fontcontent);
    //     }

    //     try {
    //         $Verification = NotUserVerification::where('phone', $phone)->get();
    //         if (count($Verification) == 0) {
    //             $verification_add = new NotUserVerification([
    //                 'phone' => $phone,
    //                 'verification_num' =>  $Code,
    //             ]);
    //             $verification_add->save();
    //             $send_sms = true;
    //         } else {
    //             // 五分鐘內 不可多次請求5次 有空再做
    //             $exist_verification =  NotUserVerification::where('phone', $phone)->first();
    //             $diff_minute = carbon::parse($now)->diffInMinutes(Carbon::parse($exist_verification->created_at), true);

    //             if ($diff_minute < 5) {
    //                 if ($exist_verification->count > 5) {
    //                     $exist_verification->update([
    //                         'send_flg' => 'N',
    //                     ]);
    //                     return $send_sms;
    //                 }
    //                 $exist_verification->update([
    //                     'verification_num' => $Code,
    //                     'count' => $exist_verification->count + 1,
    //                 ]);
    //                 $send_sms = true;
    //             } else if ($diff_minute >= 5) {
    //                 $exist_verification->update([
    //                     'verification_num' => $Code,
    //                     'created_at' => $now,
    //                     'updated_at' => $now,
    //                     'count' => 1,
    //                 ]);
    //                 $send_sms = true;
    //             }
    //         }
    //         if ($send_sms) {
    //             $username = "peng1027";
    //             $password = "o9132178966";
    //             $method = 1;
    //             $sms_msg = "歡迎您使用I-lolly代理接送，您的手機驗證碼為:$Code ，請向接待人員出示此簡訊以確認。";
    //             $phone = $phone;
    //             $urlencode_sms = urlencode($sms_msg);

    //             $url = "http://sms-get.com/api_send.php?";
    //             $url .= "username=" . $username;
    //             $url .= "&password=" . $password;
    //             $url .= "&method=" . $method;
    //             $url .= "&sms_msg=" . $urlencode_sms;
    //             $url .= "&phone=" . $phone;

    //             $result = file_get_contents($url);
    //             $obj = json_decode($result, true);

    //             if ($obj["stats"]) {
    //                 NotUserVerification::where('phone', '=', $phone)
    //                     ->update([
    //                         'send_flg' => 'Y',
    //                     ]);
    //                 return $send_sms;
    //             } else {
    //                 NotUserVerification::where('phone', '=', $phone)
    //                     ->update([
    //                         'send_flg' => 'N',
    //                     ]);
    //                 return $send_sms;
    //             }
    //             //echo $result;

    //         }
    //     } catch (\Exception $e) {
    //         return $this->succeed($e, 400);
    //     }
    // }
    public function setVerification($phone, $pass)
    {

        $send_sms = false;
        $timezone = config('services.time_zone');
        $now = Carbon::now($timezone)->format('Y-m-d H:i:s');
        $Code = "";
        //產生隨機認證碼
        for ($i = 0; $i < 4; $i++) {
            $fontcontent = rand(0, 9);
            $Code .= strval($fontcontent);
        }

        try {
            $Verification = NotUserVerification::where('phone', $phone)->get();
            if (count($Verification) == 0) {
                $verification_add = new NotUserVerification([
                    'phone' => $phone,
                    'verification_num' =>  $Code,
                ]);
                $verification_add->save();
                $send_sms = true;
            } else {
                // 五分鐘內 不可多次請求5次 有空再做
                $exist_verification =  NotUserVerification::where('phone', $phone)->first();
                $diff_minute = carbon::parse($now)->diffInMinutes(Carbon::parse($exist_verification->created_at), true);

                if ($diff_minute < 5) {
                    if ($exist_verification->count > 5) {
                        $exist_verification->update([
                            'send_flg' => 'N',
                        ]);
                        $send_sms = 'limit';
                        return $send_sms;
                    }
                    $exist_verification->update([
                        'verification_num' => $Code,
                        'count' => $exist_verification->count + 1,
                    ]);
                    $send_sms = true;
                } else if ($diff_minute >= 5) {
                    $exist_verification->update([
                        'verification_num' => $Code,
                        'created_at' => $now,
                        'updated_at' => $now,
                        'count' => 1,
                    ]);
                    $send_sms = true;
                }
            }
            if (!$pass) {
                if ($send_sms) {

                    $sms_msg = "歡迎您使用I-lolly代理接送，您的手機驗證碼為:$Code ，請向接待人員出示此簡訊以確認。";
                    $curl = curl_init();
                    // url
                    // $url = 'https://smsapi.mitake.com.tw/api/mtk/SmQuery?';//查詢餘額
                    $url = 'https://smsapi.mitake.com.tw/api/mtk/SmSend?'; //發送單封簡訊
                    $url .= 'CharsetURL=UTF-8';
                    // parameters
                    $data = 'username=85053127';
                    $data .= '&password=shoes';
                    $data .= '&dstaddr=';
                    $data .= $phone;
                    $data .= '&smbody=';
                    $data .= $sms_msg;
                    // 設定curl網址
                    curl_setopt($curl, CURLOPT_URL, $url);
                    // 設定Header
                    curl_setopt(
                        $curl,
                        CURLOPT_HTTPHEADER,
                        array("Content-type: application/x-www-form-urlencoded")
                    );
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    // 執行
                    $output = curl_exec($curl);
                    curl_close($curl);

                    $keywords = explode("statuscode=", $output);
                    $keywords = explode("\r\n", $keywords[1]);

                    if ($keywords[0] == 0 || $keywords[0] == 1 || $keywords[0] == 2 || $keywords[0] == 4) {
                        NotUserVerification::where('phone', '=', $phone)
                            ->update([
                                'send_flg' => 'Y',
                            ]);
                        $this->VerificationLog($phone, 'Api/QrcodeController/Verify', true);
                        return $send_sms;
                    } else {
                        NotUserVerification::where('phone', '=', $phone)
                            ->update([
                                'send_flg' => 'N',
                            ]);
                        $send_sms = false;
                        $this->VerificationLog($phone, 'Api/QrcodeController/Verify', false);
                        return $send_sms;
                    }
                }
            } else {
                return $send_sms;
            }
        } catch (\Exception $e) {
            $send_sms = false;
            return $send_sms;
        }
    }
    public function recordLeaveTable($token_params, $status, $today)
    {

        foreach ($token_params['user_id_arr'] as $user_id) {
            $exist_leave = Leave::where('user_id', $user_id)
                ->where('created_at', 'like', '%' . $today . '%')
                ->first();
            if ($exist_leave) {
                $exist_leave->update([
                    'phone' => $token_params['other_people']['phone'] ? $token_params['other_people']['phone'] : $token_params['parent_phone'],
                    'name' => $token_params['other_people']['phone'] ? $token_params['other_people']['name'] : $token_params['parent_name'],
                    'title' => $token_params['other_people']['phone'] ? $token_params['other_people']['title'] : $token_params['parent_title'],
                    'status' => $status,
                ]);
            }
            // else {
            //     $leave_add = new Leave([
            //         'phone' => $token_params['other_people']['phone'] ? $token_params['other_people']['phone'] : $token_params['phone'],
            //         'name' => $token_params['other_people']['phone'] ? $token_params['other_people']['name'] : $token_params['name'],
            //         'title' => $token_params['other_people']['phone'] ? $token_params['other_people']['title'] : $token_params['title'],
            //         'status' => $status,
            //     ]);
            //     $user = User::find($user_id);
            //     $user->leaves()->save($leave_add);
            // }
        }
    }
    public function teacherCheck(Request $request)
    {
        $data = json_decode($request->data, true);
        $check = $request->check;
        $teacher_id = $request->teacher_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $validator = Validator::make(
            [
                'check' => $check,
            ],
            [
                'check' => 'required|boolean',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('check was not boolean', $validator->errors(), 401);
        }

        if ($check) {
            $qrcode = Qrcode::where('parent_id', $data['parent_id'])->first();
            if (!$qrcode) { //qrcode不存在代表已經移除委託
                $data['request_exit'] = false;
                $data['leave_result'] = false;
                return $this->succeed($data, 200);
            }
            try {
                $inSchoolCheck = Leave::whereIn('user_id', $data['user_id_arr'])
                    ->where('created_at', 'like', '%' . $today . '%')
                    ->where('status', false)
                    ->get();
                if (!$inSchoolCheck->isEmpty()) {
                    $this->recordLeaveTable($data, 1, $today); //0為尚未接送 1為已接送
                    if ($teacher_id) {
                        foreach ($inSchoolCheck as $leave) {
                            $this->leaveNotify($leave, $teacher_id);
                        }
                    }
                    $data['request_exit'] = true;
                    $data['leave_result'] = true;
                    return $this->succeed($data, 200); //成功接走小孩
                } else {
                    $data['request_exit'] = true;
                    $data['leave_result'] = false;
                    return $this->succeed($data, 200); //所有小孩已被接走
                }
            } catch (\Exception $e) {
                return $this->error($e, 400);
            }
        } else {
            $this->recordLeaveTable($data, 0, $today); //0為尚未接送 1為已接送
        }
    }
    public function leaveNotify($leave, $teacher_id)
    {
        $user_id = $leave->user_id;
        $leave_id = $leave->id;
        $type_message = 'leave';
        $type_sound = 'default';
        $statu = 10;
        $user_name = User::where('id', $user_id)->with('profile')->first()->profile->name;

        $title = '[接送通知]' . $user_name;

        if ($teacher_id) {
            $teacher = User::where('id', $teacher_id)->with('profile')->first();
            $teacher_name = $teacher->profile->name;
            $message = '您的小孩已經接走了，如果有問題請立刻聯繫值班導師\nfrom' . $teacher_name . '\nphone:' . $teacher->phone;
            $relationship = User::find($user_id)->spu_relationship;
            $parent = Parents::find($relationship->parent_id);
            $notify = new Notify;
            $notify->sent_id = $teacher_id;
            $notify->sent_type = 'App\User';
            $notify->school_id = $parent->school_id;
            $notify->title = $title;
            $notify->message = $message;
            $notify->target = '家長';
            $notify->statu_id = $statu;
            //對應類型JSON
            $notify->relationship = collect([
                'type' => 'leave',
                'id' => $leave_id,
                'user_id' => $user_id,
                'user_name' => $user_name,
            ])->toJson();

            $token = $parent->device_token;

            //DB to save
            $notify->save();
            $notify->parents()->attach($parent, ['status' => 0, 'student_id' => $user_id]);

            $data = [
                'id' => $notify->id,
                'title' => $title,
                'message' => $message,
                'type' => $type_message,
                'token' => $token,
                'sound' => $type_sound,
                // 'system' => 'ios',
            ];

            $job = (new FCMNotification($data))->onConnection('redis_high');
            $this->dispatch($job);

            return $this->succeed('', 200);
        }

        return $this->error('teacher_id  undefined', 501);
    }
    public function deleteURL(Request $request)
    {
        $parent_id = $request->parent_id;
        $validator = Validator::make(
            [
                'parent_id' => $parent_id,
            ],
            [
                'parent_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('parent_id was not found', $validator->errors(), 401);
        }

        //驗證Qrcode存在
        $qrcode = Qrcode::where('parent_id', $parent_id)->first();
        if ($qrcode) { //qrcode存在
            $qrcode->delete();
            return $this->succeed('移除委託', 200);
        }
        return $this->succeed('移除委託', 200);
    }
}
