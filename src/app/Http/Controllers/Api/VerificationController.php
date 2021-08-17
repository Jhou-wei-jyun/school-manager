<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\API\ApiHelper;
use App\API\ApiVerificationLog;
use App\Parents;
use App\User;
use App\Verification;
use App\Http\Resources\Api\GetList;
use Carbon\Carbon;
use Carbon\CarbonPeriod;



class VerificationController extends Controller
{
    use ApiHelper;
    use ApiVerificationLog;

    public function index(Request $request)
    {
        $Verification = $request->Verification();

        if (!$Verification) {
            return $this->error('', 401);
        }

        echo $parents;

        return $this->succeed($parents, 200);
    }
    // 寄簡訊
    public function setVerification(Request $request)
    {

        $send_sms = false;
        $timezone = config('services.time_zone');
        $now = Carbon::now($timezone)->format('Y-m-d H:i:s');
        $send_sms = false;
        $phone = $request->phone;
        $existsphone = $this->exitsphone($phone);
        if (!$existsphone) {
            return $this->error($request, 400);
        }
        $Code = "";
        //產生隨機認證碼
        for ($i = 0; $i < 4; $i++) {
            $fontcontent = rand(0, 9);
            $Code .= strval($fontcontent);
        }

        try {
            $Verification = Verification::where('phone', $phone)->get();
            if (count($Verification) == 0) {
                $verification_add = new Verification([
                    'phone' => $phone,
                    'verification_num' =>  $Code,
                ]);
                $verification_add->save();
                $send_sms = true;
            } else {
                // 五分鐘內 不可多次請求5次 有空再做
                $exist_verification =  Verification::where('phone', $phone)->first();
                $diff_minute = carbon::parse($now)->diffInMinutes(Carbon::parse($exist_verification->created_at), true);

                if ($diff_minute < 5) {
                    if ($exist_verification->count > 5) {
                        $exist_verification->update([
                            'send_flg' => 'N',
                        ]);
                        return $this->error('超過發送上限，請等待5分鐘後重新發送', 505);
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

            if ($send_sms) {

                $sms_msg = "歡迎您使用I-lolly，您的手機驗證碼為:$Code ，請在您的裝置中輸入驗證碼。";
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
                    Verification::where('phone', '=', $phone)
                        ->update([
                            'send_flg' => 'Y',
                        ]);
                    $this->VerificationLog($phone, 'Api/VerificationController/setVerification', true);
                    return $this->succeed('簡訊發送成功', 200);
                } else {
                    Verification::where('phone', '=', $phone)
                        ->update([
                            'send_flg' => 'N',
                        ]);
                    $send_sms = false;
                    $this->VerificationLog($phone, 'Api/VerificationController/setVerification', false);
                    return $this->error('簡訊發送失敗', 504);
                }
            }
        } catch (\Exception $e) {
            $send_sms = false;
            return $this->error('簡訊發送失敗', 504);
        }
    }
    // public function setVerification(Request $request)
    // {

    //     $send_sms = false;
    //     $phone = $request->phone;
    //     $existsphone = $this->exitsphone($phone);

    //     if (!$existsphone) {
    //         return $this->error($request, 400);
    //     }

    //     $Code = "";
    //     // 產生隨機認證碼
    //     for ($i = 0; $i < 4; $i++) {
    //         $fontcontent = rand(0, 9);
    //         $Code .= strval($fontcontent);
    //     }
    //     //echo $Code;
    //     //echo $request->phone;

    //     try {
    //         $Verification = Verification::where('phone', '=', $phone)->get();

    //         $timezone = config('services.time_zone');

    //         $now = Carbon::now($timezone)->format('Y-m-d H:i:s');

    //         //print_r($Verification);
    //         if (count($Verification) == 0) {
    //             $_verification = new Verification;
    //             $_verification->phone = $phone;
    //             $_verification->verification_num = $Code;
    //             $_verification->created_at = $now;
    //             $_verification->updated_at = $now;
    //             $_verification->save();
    //             $send_sms = true;
    //             //return $this->succeed("true",200);
    //         } else {
    //             // 五分鐘內 不可多次請求5次 有空再做
    //             $v = Verification::where('phone', '=', $phone)
    //                 ->update(
    //                     array(
    //                         'verification_num' => $Code,
    //                         'updated_at' =>  $now,
    //                     )
    //                 );

    //             $send_sms = true;
    //             // 更新認證碼，有效時間10 分鐘
    //             // 尚未過期可使用
    //             // $Verification = $Verification->first();
    //             // //echo $now = Carbon::now($timezone)->format('Y-m-d H:i:s');

    //             // $date15time = Carbon::parse($Verification->updated_at, $timezone)->addMinutes(15);
    //             // $todaynow = Carbon::now($timezone);

    //             // if(strtotime($date15time) < strtotime($todaynow)){
    //             // }

    //         }
    //         if ($send_sms) {
    //             $username = "peng1027";
    //             $password = "o9132178966";
    //             $method = 1;
    //             $sms_msg = "歡迎您使用I-lolly，您的手機驗證碼為:$Code ，請在您的裝置中輸入驗證碼。";
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


    //             $v = Verification::where('phone', '=', $phone)
    //                 ->update(
    //                     array(
    //                         'send_flg' => $obj["stats"],
    //                         'updated_at' =>  $now,
    //                     )
    //                 );
    //             if ($obj["stats"]) {
    //                 $v = Verification::where('phone', '=', $phone)
    //                     ->update(
    //                         array(
    //                             'send_flg' => 'Y',
    //                         )
    //                     );
    //                 return $this->succeed("Send Succeed!", 200);
    //             } else {
    //                 $v = Verification::where('phone', '=', $phone)
    //                     ->update(
    //                         array(
    //                             'send_flg' => 'N',
    //                         )
    //                     );
    //                 return $this->succeed("Send Fail", 400);
    //             }
    //             //echo $result;

    //         }
    //         // }else{
    //         //     // 更新認證碼，有效時間10 分鐘
    //         //     // 尚未過期可使用
    //         //     $now = Carbon::now($timezone)->format('Y-m-d H:i:s');
    //         //     $date15time = Carbon::parse($Verification->updated_at, $timezone)->addMinutes(15);
    //         //     $todaynow = Carbon::now($timezone);

    //         //     if(strtotime($date15time) < strtotime($todaynow)){


    //     } catch (Exception $e) {
    //         return $this->succeed($e, 400);
    //     }

    //     return $this->succeed("Sent fail", 400);
    // }

    public function VerificationLoginCheck(Request $request) //沒用到
    {
        $phone = $request->phone;
        $code = $request->code;

        $existsphone = $this->exitsphone($phone);

        if (!$existsphone) {
            return $this->error($request, 400);
        }

        $Verification = Verification::where('phone', '=', $phone)
            ->where('verification_num', '=', $code)->get();

        if (count($Verification) == 0) {
            return $this->succeed("Check Fail", 400);
        } else {
            $timezone = config('services.time_zone');
            $now = Carbon::now($timezone)->format('Y-m-d H:i:s');
            // 更新登入時間
            // login_count 歸0
            $v = Verification::where('phone', '=', $phone)->where('verification_num', '=', $code)
                ->update(
                    array(
                        'login_date' => $now,
                        'login_count' => 1 //0未過,1已過
                    )
                );



            return $this->succeed("Check Succeed", 200);
        }
    }
    public function getApiToken(Request $request) //login 回傳 api_token
    {
        $phone = $request->phone;
        $user = User::where('phone', $phone)->first();
        $parent = Parents::where('phone', $phone)->first();
        if ($user !== null) {
            $user->update([
                'api_token' => Str::random(60),
            ]);
            Auth::login($user);
        }
        if ($parent !== null) {
            $parent->update([
                'api_token' => Str::random(60),
            ]);
            Auth::login($parent);
        }
        $api_token = Auth::user()->api_token;
        return $this->succeed($api_token, 200);
    }

    //電話是否存在
    public function exitsphone(String $Phone)
    {
        $parents = Parents::where('phone', '=', $Phone)->get();

        if (count($parents) > 0) {
            return true;
        } else {
            // 判定老師的
            $users = User::where('phone', '=', $Phone)
                ->whereNotIn('position_id', [10])
                ->get();
            if (count($users) > 0) {

                return true;
            }

            return false;
        }
        return false;
    }
    public function checkparents2(Request $request)
    {
        try {
            $ReturnStatus = [
                'exists' => false,
                'Identity'  => '',
            ];

            $parents = Parents::where('phone', '=', $request->phone)->get();

            if (count($parents) > 0) {
                $ReturnStatus["exists"] = true;
                $ReturnStatus["Identity"] = "Parent";
                return $this->succeed($ReturnStatus, 200);
            } else {
                // 判定老師的
                $users = User::where('phone', '=', $request->phone)
                    ->whereNotIn('position_id', [1, 10, 20])
                    ->get();
                if (count($users) > 0) {
                    $ReturnStatus["exists"] = true;
                    $ReturnStatus["Identity"] = "Teacher";
                    return $this->succeed($ReturnStatus, 200);
                }
                $ReturnStatus["exists"] = false;
                $ReturnStatus["Identity"] = "Nothing";
                return $this->succeed($ReturnStatus, 200);
            }
        } catch (Exception $e) {
            return $this->succeed($request, 400);
        }

        return $this->succeed($request, 400);
    }
}
