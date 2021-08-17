<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\API\ApiHelper;
use App\Parents;
use App\User;
use App\Http\Resources\Api\GetList;
use Exception;
use App\Jobs\FCMNotification;


class ParentsController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        $parents = $request->parents();

        if (!$parents) {
            return $this->error('', 401);
        }

        echo $parents;

        return $this->succeed($parents, 200);
    }

    public function checkparents(Request $request)
    {
        try {
            $parents = Parents::where('phone', '=', $request->phone)->get();

            if (count($parents) > 0) {
                return $this->succeed("true", 200);
            } else {
                $users = User::where('phone', '=', $request->phone)->get();
                if (count($users) > 0) {
                    return $this->succeed("true", 200);
                }
                return $this->succeed("false", 200);
            }
        } catch (Exception $e) {
            return $this->succeed($request, 400);
        }

        return $this->succeed($request, 400);
    }

    public function checkparents2(Request $request)
    {
        $phone = $request->phone;
        try {
            $ReturnStatus = [
                'exists' => false,
                'Identity'  => '',
                'school_id' => '',
                'login' => false,
            ];

            $parents = Parents::where('phone', $phone)->get();
            if ($parents->isNotEmpty()) {
                if (is_null($parents[0]->device_token)) {
                    $ReturnStatus["exists"] = true;
                    $ReturnStatus["Identity"] = "Parent";
                    $ReturnStatus["school_id"] = $parents[0]->school_id;
                    // $ReturnStatus["login"] = true;
                } else {
                    $ReturnStatus["exists"] = true;
                    $ReturnStatus["Identity"] = "Parent";
                    $ReturnStatus["school_id"] = $parents[0]->school_id;
                    // $ReturnStatus["login"] = false;
                }
                return $this->succeed($ReturnStatus, 200);
            } else {
                // 判定老師的
                $users = User::where('phone', '=', $request->phone)->get();
                if ($users->isNotEmpty()) {
                    if ($users[0]->device_token === null) {

                        $ReturnStatus["exists"] = true;
                        $ReturnStatus["Identity"] = "Teacher";
                        $ReturnStatus["school_id"] = $users[0]->school_id;
                        // $ReturnStatus["login"] = true;
                    } else {
                        $ReturnStatus["exists"] = true;
                        $ReturnStatus["Identity"] = "Teacher";
                        $ReturnStatus["school_id"] = $users[0]->school_id;
                        // $ReturnStatus["login"] = false;
                    }
                }
                return $this->succeed($ReturnStatus, 200);
            }

            $ReturnStatus["exists"] = false;
            $ReturnStatus["Identity"] = "Nothing";
            $ReturnStatus["school_id"] = "";
            // $ReturnStatus["login"] = false;
            return $this->succeed($ReturnStatus, 200);
        } catch (Exception $e) {
            $ReturnStatus["exists"] = false;
            $ReturnStatus["Identity"] = "Nothing";
            $ReturnStatus["school_id"] = "";
            // $ReturnStatus["login"] = false;
            return $this->succeed($ReturnStatus, 400);
        }
    }

    public function UpdataParentDeviceToken(Request $request)
    {
        $phone = $request->phone;
        $title = $request->title;
        $new_device_token = $request->dt;
        $parent = Parents::where('phone', $phone)->first();
        if ($parent->device_token !== $new_device_token) {
            try {
                $type_message = 'sign_out';
                $type_sound = 'default';
                $message = 'sign_out';
                $token = $parent->device_token;
                $data = [
                    'id' => $parent->parent_id,
                    'title' => '登出通知',
                    'message' => $message,
                    'type' => $type_message,
                    'token' => $token,
                    'sound' => $type_sound,
                ];
                $job = (new FCMNotification($data))->onConnection('redis_high');
                $this->dispatch($job);
                Parents::where('phone',  $phone)
                    ->update([
                        "title" => $title,
                        "device_token" => $new_device_token,
                    ]);
                return $this->succeed('更新DeviceToken', 200);
            } catch (Exception $e) {

                return $this->error($e, 400);
            }
        } else {
            return $this->succeed('相同裝置', 200);
        }
    }
}
