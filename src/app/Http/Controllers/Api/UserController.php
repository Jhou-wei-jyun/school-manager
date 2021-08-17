<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\API\ApiHelper;
use App\Parents;
use App\spu_relationship;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Jobs\FCMNotification;
use Exception;


class UserController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        $user_id = (int)$request->user_id;
        //驗證
        $validator = Validator::make(
            [
                'user_id' => $user_id,
            ],
            [
                'user_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }
        $age = null;
        $user = User::where('id', $user_id)->with('profile')->with('department.teacher.profile')->first();
        $parent = Parents::find(spu_relationship::where('user_id', $user_id)->first()->parent_id);
        if ($user->profile->birthday != null) {
            $timezone = config('services.time_zone');
            $today = Carbon::today($timezone);
            $birthday = Carbon::parse($user->profile->birthday, $timezone);
            $diff = $today->diff($birthday);
            $age = $diff->y . '歲' . $diff->m . '月' . $diff->d . '天';
        }

        return [
            'user_id' => $user->id,
            'name' => $user->profile->name,
            'gender' => $user->profile->gender,
            'birthday' => $user->profile->birthday,
            'age' => $age,
            'nickname' => $user->profile->nickname,
            'height' => $user->profile->height,
            'weight' => $user->profile->weight,
            'avatar' => 'avatar/small/' . $user->profile->avatar,
            'parent_id' => $parent->parent_id,
            'title' => $parent->title,
            'parent_phone' => $parent->phone,
            'address' => $parent->address,
            'department_id' => $user->department->id,
            'department_name' => $user->department->name,
            'teacher_id' => $user->department->teacher->id,
            'teacher_name' => $user->department->teacher->profile->name,
            'teacher_phone' => $user->department->teacher->phone,
        ];
    }

    public function update(Request $request)
    {
        $user_id = (int)$request->user_id;
        $editColumn = (string)$request->editColumn;
        $edit = (string)$request->edit;
        //驗證
        $validator = Validator::make(
            [
                'user_id' => $user_id,
            ],
            [
                'user_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }
        if ($editColumn == 'address') {
            $parent_id = spu_relationship::where('user_id', $user_id)->first()->parent_id;
            Parents::find($parent_id)->update([
                $editColumn => $edit,
            ]);
        } else {
            User::find($user_id)->profile()->update([
                $editColumn => $edit,
            ]);
        }
        return $this->succeed('', 200);
    }

    public function updateDeviceToken(Request $request)
    {
        $user = $request->user();

        $device_token = $request->dt;

        if (!$user || !$device_token) {
            return $this->error('ID or Device Token is required!', 400);
        }

        $user->device_token = $device_token;
        $user->save();

        return $this->succeed('', 200);
    }

    public function updateTeacherDeviceToken(Request $request)
    {
        $teacher_id = $request->id;
        $new_device_token = $request->dt;
        $teacher = User::find($teacher_id);

        if ($teacher->device_token !== $new_device_token) {
            try {
                $type_message = 'sign_out';
                $type_sound = 'default';
                $message = 'sign_out';
                $token = $teacher->device_token;
                $data = [
                    'id' => $teacher->id,
                    'title' => '登出通知',
                    'message' => $message,
                    'type' => $type_message,
                    'token' => $token,
                    'sound' => $type_sound,
                ];
                $job = (new FCMNotification($data))->onConnection('redis_high');
                $this->dispatch($job);
                User::where('id', $teacher_id)
                    ->update(
                        [
                            'device_token' => $new_device_token,
                        ]
                    );
                return $this->succeed('更新DeviceToken', 200);
            } catch (Exception $e) {

                return $this->error($e, 400);
            }
        } else {
            return $this->succeed('相同裝置', 200);
        }
    }
    public function getAvatar(Request $request)
    {
        $userid = $request->id;

        $avatar = User::find($userid)->profile->avatar;
        return $this->succeed('avatar/small/' . $avatar, 200);
    }
    public function updateUserAvatar(Request $request)
    {
        $userid = $request->id;
        $avatar = $request->avatar;


        $users = User::where('id', '=', $userid)
            ->update(
                array(
                    'avatar' => $avatar,
                )
            );
    }
    public function editInfo(Request $request)
    {
        //必須
        $user_id = $request->user_id;
        $edit_type = $request->type;
        //擇一
        $name = $request->name;
        $gender = $request->gender;
        $birthday = $request->birthday;

        $validator = Validator::make(
            [
                'user_id' => $user_id,
                'edit_type' => $edit_type,
            ],
            [
                'user_id' => 'required',
                'edit_type' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }


        $user = User::find($user_id);
        if ($name && $edit_type == 'name') {
            $user->profile()
                ->update([
                    'name' => $name,
                ]);
            return $this->succeed('', 200);
        } elseif ($gender && $edit_type == 'gender') {
            $user->profile()
                ->update([
                    'gender' => $gender,
                ]);
            return $this->succeed('', 200);
        } elseif ($birthday && $edit_type == 'birthday') {
            $user->profile()
                ->update([
                    'birthday' => $birthday,
                ]);
            return $this->succeed('', 200);
        }
        return $this->error('undefined value', 400);
    }
}
