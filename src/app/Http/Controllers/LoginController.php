<?php

namespace App\Http\Controllers;

use App\Verification;
use App\User;
use App\Admin;
use App\School;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\API\ApiHelper;
use App\API\ApiVerificationLog;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ApiHelper;
    use ApiVerificationLog;

    public function adminRegister(Request $request)
    {
        $name = $request->name;
        $school_id = $request->school_id;
        $account = $request->account;
        $password = $request->password;
        $verificationToken = $request->verificationToken;
        if ($verificationToken === '28fjdfnjnu1ieojmcsdw') {
            $hashedPassword = Hash::make($password);

            $admin_add = new Admin([
                'name' => $name,
                'account' => $account,
                'password' => $hashedPassword,
                'school_id' => $school_id,
                'level_id' => 10,
                'group_id' => 2,
            ]);
            $admin_add->save();
        } else {
            return $this->succeed('Anndabaka', 200);
        }
    }
    public function register(Request $request)
    {
        $data = (object)$request->phone;
        $phone = $data->phone;
        $token = $data->token;

        $account = $request->account;
        $password = $request->password;
        $validator = Validator::make(
            [
                'phone' => $phone,
                'account' => $account,
                'password' => $password,
            ],
            [
                'phone' => 'required',
                'account' => 'required',
                'password' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('電話帳號密碼為填寫項目', $validator->errors(), 401);
        }


        $validator = Validator::make(
            [
                'account' => $account,
            ],
            [
                'account' => 'unique:admins',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('此帳號已被人使用', $validator->errors(), 402);
        }

        $Verification = Verification::where('phone', $phone)->first();
        if ($token === null || $token !== $Verification->token) {
            return $this->error('電話號碼尚未驗證', 507);
        }

        $exist_user = User::where('phone', $phone)->first();
        $hashedPassword = Hash::make($password);

        $admin_add = new Admin([
            'name' => $exist_user->profile->name,
            'account' => $account,
            'password' => $hashedPassword,
            'school_id' => $exist_user->school_id,
            'user_id' => $exist_user->id,
        ]);
        $admin_add->save();
        return $this->succeed('註冊成功，請重新登入', 200);
    }
    public function login(Request $request)
    {
        $account = $request->account;
        $password = $request->password;
        $validator = Validator::make(
            [
                'account' => $account,
                'password' => $password,
            ],
            [
                'account' => 'required',
                'password' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('帳號密碼為填寫項目', $validator->errors(), 401);
        }
        $attempt = Auth::attempt([
            'account' => $account,
            'password' => $password
        ]);

        // return Auth::check() ? '1' : "2";
        if ($attempt) {
            $login_admin = Auth::user();
            $school_info = School::find($login_admin->school_id);
            if ($login_admin->group_id == null) {
                return $this->error('權限不足', 502);
            }

            $login_admin->update([
                'api_token' => Str::random(60),
            ]);

            activity()
                ->causedBy($login_admin)
                ->performedOn($login_admin)
                ->withProperties(['type' => 'login'])
                ->log('Web登入');

            $data = [
                'id' => $login_admin->id,
                'name' => $login_admin->name,
                'account' => $login_admin->account,
                'api_token' => $login_admin->api_token,
                'group_id' => $login_admin->group_id,
                'avatar' => null,
                'teacher_id' => $login_admin->user_id,
                'school' => $login_admin->school_id,
                'school_info' => $school_info,
                'teacher_type' => $school_info->teacher_type,
                'student_type' => $school_info->student_type,
                'permission' => $login_admin->level->details,
            ];

            return $this->succeed($data, 200);
        } else {
            return $this->errors('帳號密碼不符', $validator->errors(), 501);
        }
    }
    public function logout()
    {
        // $id = Admin::find(10);
        Auth::guard('web')->logout();
        // Auth::logout();
    }
    public function forgotPassword(Request $request)
    {
        $account = $request->account;
        $data = (object)$request->phone;

        $phone = $data->phone;
        $token = $data->token;
        $validator = Validator::make(
            [
                'phone' => $phone,
            ],
            [
                'phone' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('電話為填寫項目', $validator->errors(), 401);
        }

        $Verification = Verification::where('phone', $phone)->first();
        if ($token === null || $token !== $Verification->token) {
            return $this->error('電話號碼尚未驗證', 507);
        }
        $exist_user = User::where('phone', $phone)->first();
        $exist_admin = Admin::where('account', $account)
            ->where('user_id', $exist_user->id)
            ->first();
        if ($exist_admin !== null) {
            return $this->succeed([
                'id' => $exist_admin->id,
                'token' => $exist_admin->api_token,
            ], 200);
        } else {
            return $this->errors('使用者不存在', $validator->errors(), 505);
        }
    }
    public function resetPassword(Request $request)
    {
        $data = (object)$request->data;
        $id = $data->id;
        $api_token = $data->token;
        $new_password = $data->newPassword;
        $validator = Validator::make(
            [
                'id' => $id,
                'new_password' => $new_password,
            ],
            [
                'id' => 'required',
                'new_password' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('密碼為填寫項目', $validator->errors(), 401);
        }

        $admin = Admin::find($id);
        if ($admin->api_token !== $api_token) {
            return $this->error('驗證失敗', 507);
        }
        $hashedPassword = Hash::make($new_password);

        try {
            // Get the updated rows count here. Keep in mind that zero is a
            // valid value (not failure) if there were no updates needed
            $admin->update([
                'password' => $hashedPassword,
            ]);
            return $this->succeed('變更成功', 200);
        } catch (\Illuminate\Database\QueryException $e) {
            // Do whatever you need if the query failed to execute
            return $this->errors($e, $validator->errors(), 600);
        }
    }
    public function setPhoneVerification(Request $request)
    {
        $timezone = config('services.time_zone');
        $now = Carbon::now($timezone)->format('Y-m-d H:i:s');
        $send_sms = false;
        $phone = $request->phone;
        $forgot_password = $request->forgot_password; //Bool
        $exist_phone = User::where('phone', $phone)->first();
        $validator = Validator::make(
            [
                'phone' => $phone,

            ],
            [
                'phone' => 'required',

            ]
        );
        if ($validator->fails()) {
            return $this->errors('電話為填寫項目', $validator->errors(), 401);
        }
        if ($exist_phone === null) {
            return $this->errors('此電話不存在', $validator->errors(), 505);
        }
        $check_admin_exist = Admin::where('user_id', $exist_phone->id)->get();

        if (!$forgot_password) {
            if ($check_admin_exist->count() >= 1) {
                return $this->errors('此電話號碼已註冊', $validator->errors(), 506);
            }
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
                    'token' => Str::random(60),
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
                        'token' => Str::random(60),
                    ]);
                    $send_sms = true;
                } else if ($diff_minute >= 5) {
                    $exist_verification->update([
                        'verification_num' => $Code,
                        'created_at' => $now,
                        'updated_at' => $now,
                        'count' => 1,
                        'token' => Str::random(60),
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
                    if (!$forgot_password) {
                        $this->VerificationLog($phone, 'Web/LoginController/register', true);
                    } else {
                        $this->VerificationLog($phone, 'Web/LoginController/resetPassword', true);
                    }

                    return $this->succeed('簡訊發送成功', 200);
                } else {
                    Verification::where('phone', '=', $phone)
                        ->update([
                            'send_flg' => 'N',
                        ]);
                    $send_sms = false;
                    if (!$forgot_password) {
                        $this->VerificationLog($phone, 'Web/LoginController/register', false);
                    } else {
                        $this->VerificationLog($phone, 'Web/LoginController/resetPassword', false);
                    }
                    return $this->error('簡訊發送失敗', 504);
                }
            }
        } catch (\Exception $e) {
            $send_sms = false;
            return $this->error('簡訊發送失敗', 504);
        }
    }
    // public function setPhoneVerification(Request $request)
    // {
    //     $send_sms = false;
    //     $phone = $request->phone;
    //     $forgot_password = $request->forgot_password; //Bool
    //     $exist_phone = User::where('phone', $phone)->first();
    //     $validator = Validator::make(
    //         [
    //             'phone' => $phone,

    //         ],
    //         [
    //             'phone' => 'required',

    //         ]
    //     );
    //     if ($validator->fails()) {
    //         return $this->errors('電話為填寫項目', $validator->errors(), 401);
    //     }
    //     if ($exist_phone === null) {
    //         return $this->errors('此電話不存在', $validator->errors(), 505);
    //     }
    //     $check_admin_exist = Admin::where('user_id', $exist_phone->id)->get();

    //     if (!$forgot_password) {
    //         if ($check_admin_exist->count() >= 1) {
    //             return $this->errors('此電話號碼已註冊', $validator->errors(), 506);
    //         }
    //     }

    //     $Code = "";
    //     // 產生隨機認證碼
    //     for ($i = 0; $i < 4; $i++) {
    //         $fontcontent = rand(0, 9);
    //         $Code .= strval($fontcontent);
    //     }

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
    //                     [
    //                         'verification_num' => $Code,
    //                         'updated_at' =>  $now,
    //                     ]
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
    //                         'token' => Str::random(60),
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


    //     } catch (\Exception $e) {
    //         return $this->succeed($e, 400);
    //     }

    //     return $this->succeed("Sent fail", 400);
    // }
    public function checkValidity(Request $request)
    {

        $phone = $request->phone;
        $code = $request->code;
        // return $this->succeed([
        //     'phone' => $phone,
        //     'verified' => true,
        //     'token' => 'abc',
        // ], 200);
        $validator = Validator::make(
            [
                'phone' => $phone,
                'code' => $code,

            ],
            [
                'phone' => 'required',
                'code' => 'required',

            ]
        );
        if ($validator->fails()) {
            return $this->errors('請填入驗證碼', $validator->errors(), 401);
        }

        $Verification = Verification::where('phone', $phone)->first();
        if ($Verification !== null) {
            if ($code === $Verification->verification_num) {
                return $this->succeed([
                    'phone' => $phone,
                    'verified' => true,
                    'token' => $Verification->token,
                ], 200);
            } else {
                return $this->errors('驗證碼錯誤', $validator->errors(), 501);
            }
        } else {
            return $this->errors('此電話不存在', $validator->errors(), 505);
        }
    }
    public function accountValidity(Request $request)
    {
        $account = $request->account;
        $validator = Validator::make(
            [
                'account' => $account,
            ],
            [
                'account' => 'unique:admins',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('此帳號已被人使用', $validator->errors(), 402);
        }
        return $this->succeed('帳號尚未被使用', 200);
    }
    public function getAccount(Request $request)
    {
        $phone = $request->phone;
        try {
            $account = User::where('phone', $phone)->first()->admin->account;
            if ($account) {
                return $this->succeed($account, 200);
            } else {
                return $this->error('帳號不存在', 402);
            }
        } catch (\Exception $e) {
            return $this->error('帳號不存在', 400);
        }
    }
}
