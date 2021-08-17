<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Carbon\Carbon;
use App\API\ImageCompress;
use App\API\ApiHelper;

use App\Admin;
use App\User;
use App\UserType;
use App\Mac;
use App\Uuid;
use App\Profile;
use App\School;
use App\Department;
use App\Machine;
use App\spu_relationship;
use App\Area;
use App\Jobs\FaceScan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaceMachineController extends Controller
{
    use Apihelper;
    use ImageCompress;

    // public function client()
    // {
    //     // $client = new Client(['base_uri' => 'http://118.163.212.125:9526/api/']);
    //     $client = new Client(['base_uri' => 'http://10.112.10.61:8889/api/']);
    //     return $client;
    // }
    public function active_log($cause_id, $performed, $type, $result, $log)
    {
        if ($performed == null) { //連線失敗
            activity()
                ->causedBy(Admin::find((int) $cause_id))
                ->withProperties([
                    'type' => $type,
                    'result' => $result
                ])
                ->log($log);
        } else {
            activity()
                ->causedBy(Admin::find((int) $cause_id))
                ->performedOn($performed)
                ->withProperties([
                    'type' => $type,
                    'result' => $result
                ])
                ->log($log);
        }
    }

    public function device_check($school_id)
    {
        $school = School::find($school_id);
        $machines = $school->machines()->select('serial_number', 'base_uri')->get();
        $machines_count =  count($machines);
        $count = 1;
        foreach ($machines as $machine) {
            $client = $this->client($machine->base_uri);
            $response = $client->post('/api/devices/status', [
                'json' => [
                    "device_serial"    => $machine->serial_number,
                ]
            ]);
            //get response
            $code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK
            $body = json_decode($response->getBody());
            if ($code == 200) {
                //data true/false 全連線/有未連線
                if ($body->data == true) {
                    //最後一筆成功return success
                    if ($count == $machines_count) {
                        return $this->succeed('', 200);
                    }
                    $count = $count + 1;
                    continue;
                }
                if ($body->data == false) {
                    break;
                }
            } else {
                break; //200以外終止迴圈return error
            }
        }
        return $this->error('檢查連線時發生問題', 400);
    }
    public function client($base_uri)
    {
        $client = new Client(['base_uri' => "http://" . $base_uri]);
        return $client;
    }
    public function teacher_file_update($user, $avatar_file, $current)
    {
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        $avatar_name = "T" . $user->id . "-" . $current . ".jpg";
        $avatar_path = $storageFile . 'avatar/' . $avatar_name;
        $avatar_small_path = $storageFile . 'avatar/small/' . $avatar_name;
        Storage::disk($storage)->delete('/avatar/' . $user->profile->avatar);
        Storage::disk($storage)->delete('/avatar/small/' . $user->profile->avatar);
        Storage::disk($storage)->putFileAs($storageFile . 'avatar', $avatar_file, $avatar_name);
        $this->compressSmallIMG($avatar_path, $avatar_small_path);

        return $avatar_name;
    }
    public function student_file_update($user, $avatar_file, $current)
    {
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        $avatar_name = "S" . $user->id . "-" . $current . ".jpg";
        $avatar_path = $storageFile . 'avatar/' . $avatar_name;
        $avatar_small_path = $storageFile . 'avatar/small/' . $avatar_name;
        Storage::disk($storage)->delete('/avatar/' . $user->profile->avatar);
        Storage::disk($storage)->delete('/avatar/small/' . $user->profile->avatar);
        Storage::disk($storage)->putFileAs($storageFile . 'avatar', $avatar_file, $avatar_name);
        $this->compressSmallIMG($avatar_path, $avatar_small_path);

        return $avatar_name;
    }
    public function student_school_type($user_id)
    {
        $student_type = User::find($user_id)->school->student_type;

        return $student_type;
    }
    public function teacher_school_type($teacher_id)
    {
        $teacher_type = User::find($teacher_id)->school->teacher_type;

        return $teacher_type;
    }
    public function student_update_avatar(Request $request)
    {
        //驗證
        $validator = Validator::make(
            [
                'avatar' => $request->avatar,
            ],
            [
                'avatar' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('相片為未上傳', $validator->errors(), 401);
        }
        $user_id = $request->user_id;
        $admin_id = $request->admin_id;
        $school_id = $request->school_id;
        $avatar_file = $request->file('avatar_file');
        $avatar = $request->avatar;
        $avatar  = base64_encode(file_get_contents($avatar));
        $timezone = config('services.time_zone');
        $current = Carbon::now($timezone)->timestamp;
        $student_type = $this->student_school_type($user_id);
        //get user information
        $user = User::find($user_id);

        if ($student_type == '1') {
            if ($avatar_file) {
                $avatar_name = $this->student_file_update($user, $avatar_file, $current);
                $user->profile()
                    ->update([
                        'avatar' => $avatar_name,
                    ]);
            }
            //log
            if ($admin_id) {
                $this->active_log($admin_id, $user, 'edit', 'success', '編輯學生相片');
            }
            $this->FaceScan();
            return $this->succeed('', 200);
        } else if ($student_type == '2' || $student_type == '3') {
            //持有機台連線確認
            $result = $this->device_check($school_id);
            if ($result->getData()->result == true) {
                //連線成功
                $result = $this->student_update($school_id, $user, $avatar, $avatar_file, $current);
                if ($result->getData()->result == true) {
                    //log
                    if ($admin_id) {
                        $this->active_log($admin_id, $user, 'edit', 'success', '編輯學生相片');
                    }
                    $this->FaceScan();
                    return $this->succeed('更新成功', 200);
                } else {
                    if ($admin_id) {
                        $this->active_log($admin_id, $user, 'edit', 'faild', '編輯學生相片');
                    }
                    return $this->error('更新失敗', 400);
                }
            } else {
                //連線失敗
                if ($admin_id) {
                    $this->active_log($admin_id, null, 'edit', 'cannot connect', '編輯學生相片');
                }
                return $this->error('連線失敗', 400);
            }
        }
    }

    public function teacher_update_avatar(Request $request)
    {
        //驗證
        $validator = Validator::make(
            [
                'avatar' => $request->avatar,
            ],
            [
                'avatar' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('相片未上傳', $validator->errors(), 401);
        }
        $teacher_id = $request->teacher_id;
        $admin_id = $request->admin_id;
        $school_id = $request->school_id;
        $avatar_file = $request->file('avatar_file');
        $avatar = $request->avatar;
        $avatar  = base64_encode(file_get_contents($avatar));
        $timezone = config('services.time_zone');
        $current = Carbon::now($timezone)->timestamp;
        $teacher_type = $this->teacher_school_type($teacher_id);
        //get user information
        $user = User::find($teacher_id);

        if ($teacher_type == '1') {
            if ($avatar_file) {
                $avatar_name = $this->teacher_file_update($user, $avatar_file, $current);
                $user->profile()
                    ->update([
                        'avatar' => $avatar_name,
                    ]);
            }
            //log
            if ($admin_id) {
                $this->active_log($admin_id, $user, 'edit', 'success', '編輯教師相片');
            }
            $this->FaceScan();
            return $this->succeed('', 200);
        } else if ($teacher_type == '2' || $teacher_type == '3') {
            //持有機台連線確認
            $result = $this->device_check($school_id);
            if ($result->getData()->result == true) {
                //連線成功
                $result = $this->teacher_update($school_id, $user, $avatar, $avatar_file, $current);
                if ($result->getData()->result == true) {
                    if ($admin_id) {
                        $this->active_log($admin_id, $user, 'edit', 'success', '編輯教師相片');
                    }
                    $this->FaceScan();
                    return $this->succeed('更新成功', 200);
                } else {
                    if ($admin_id) {
                        $this->active_log($admin_id, $user, 'edit', 'faild', '編輯教師相片');
                    }
                    return $this->error('更新失敗', 400);
                }
            } else {
                //連線失敗
                if ($admin_id) {
                    $this->active_log($admin_id, null, 'edit', 'cannot connect', '編輯教師相片');
                }
                return $this->error('連線失敗', 400);
            }
        }
    }
    public function teacher_update($school_id, $user, $avatar, $avatar_file, $current)
    {
        $school = School::find($school_id);
        $machines = $school->machines()->select('serial_number', 'base_uri')->get();
        $machines_count =  count($machines);
        $completes = [];
        $count = 1;
        foreach ($machines as $machine) {
            //make request to face machine
            $client = $this->client($machine->base_uri);
            $response = $client->post('/api/processPerson', [
                'json' => [
                    "uuid"           => $user->user_type->uuid->uuid,
                    "person_name"   => $user->profile->name,
                    "person_id"     => "T" . $user->id,
                    "org_id"       => $user->school_id,
                    "person_code"   => "T" . $user->id,
                    "device_serial"    => $machine->serial_number,
                    "face_base64"   => $avatar,
                    "face_name"     => "T" . $user->id . "-" . $current . ".jpg",
                    "is_active"     => 1
                ]
            ]);
            //get response
            $code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK
            $body = json_decode($response->getBody());

            if ($body->code == 200) {
                if ($count == $machines_count) { //為最後一台設備時（前面皆成功）
                    if ($avatar_file) {
                        $avatar_name = $this->teacher_file_update($user, $avatar_file, $current);
                        $user->profile()
                            ->update([
                                'avatar' => $avatar_name,
                            ]);
                    }
                    return $this->succeed('', 200);
                }
                $count = $count + 1;
                $complete_uri['base_uri'] = $machine->base_uri; //紀錄已完成機台
                $completes[] = $machine->serial_number; //紀錄已完成機台
                continue;
            } else {
                if ($count != 1) {
                    //第二筆之後失敗，前面設備重置avatar
                    //取得舊avatar_base64
                    $old_avatar = 'avatar/small/' . $user->profile->avatar;
                    $old_avatar = base64_encode(file_get_contents($old_avatar));
                    $completes;
                    foreach ($completes as $complete) {
                        $client = $this->client($complete_uri['base_uri']);
                        $response = $client->post('/api/processPerson', [
                            'json' => [
                                "uuid"           => $user->user_type->uuid->uuid,
                                "person_name"   => $user->profile->name,
                                "person_id"     => "T" . $user->id,
                                "org_id"       => $user->school_id,
                                "person_code"   => "T" . $user->id,
                                "device_serial"    => $machine->serial_number,
                                "face_base64"   => $avatar,
                                "face_name"     => "T" . $user->id . "-" . $current . ".jpg",
                                "is_active"     => 1
                            ]
                        ]);
                    }
                }
                break;
            }
            return $this->error('', 400);
        }
    }
    //單數設備
    // public function student_store_old(Request $request)
    // {
    //     $school_id = $request->school_id;
    //     $name = $request->name;
    //     //mac -> XX:XX:XX:XX
    //     $uuid = $request->uuid;
    //     $mac = $request->mac;
    //     if($mac) {//mac存在
    //         //驗證
    //         $mac_array = str_split($mac, 2);
    //         $mac = join(':', $mac_array);
    //         $validator = Validator::make([
    //             'mac' => $mac,
    //         ],
    //         [
    //             'mac' => 'required|unique:macs',
    //         ]
    //         );
    //         if ($validator->fails()) {
    //             return $this->errors('fails', $validator->errors(), 401);
    //         }
    //     }
    //     $gender = $request->gender;
    //     $avatar_file = $request->file('avatar_file');
    //     $avatar = $request->avatar;
    //     if ($avatar) {
    //         $avatar  = base64_encode(file_get_contents($avatar));
    //     }
    //     $school = School::find($school_id);
    //     $student_type = $school->student_type;
    //     //可有可無
    //     $department_id = $request->department_id;
    //     $parent_id = $request->parent_id;
    //     //使用臉部驗證
    //     if ($student_type == '2' || $student_type == '3') {
    //         $validator = Validator::make([
    //             'avatar' => $avatar,
    //             'avatar_file' => $avatar_file,
    //         ],
    //         [
    //             'avatar' => 'required',
    //             'avatar_file' => 'required',
    //         ]
    //         );
    //         if ($validator->fails()) {
    //             return $this->errors('fails', $validator->errors(), 401);
    //         }
    //         $uuid = Str::random(36);//生成uuid
    //     }


    //     $timezone = config('services.time_zone');
    //     $today = Carbon::now($timezone)->format('Y-m-d');
    //     $onboard_date = $today;


    //     $serial_number = $school->machines()->first()->serial_number;

    //     //first save to get id
    //     $user_add = new User([
    //         'department_id' => $department_id ? $department_id : null,
    //         'position_id' => 10,
    //         'onboard_date' => $onboard_date,
    //     ]);
    //     $user_type_add = new UserType([
    //         'type_id' => $student_type,
    //     ]);
    //     $profile_add = new Profile([
    //         "name" => $name,
    //         "gender" => $gender,
    //     ]);
    //     if ($uuid) {
    //         $uuid_add = new Uuid([
    //             'uuid' => $uuid,
    //         ]);
    //     }
    //     if ($mac) {
    //         $mac_add = new Mac([
    //             'mac' => $mac,
    //         ]);
    //     }
    //     //save
    //     $school->users()->save($user_add)->user_type()->save($user_type_add);
    //     if ($uuid) {
    //         $user_type_add->uuid()->save($uuid_add);
    //     }
    //     if ($mac) {
    //         $user_type_add->mac()->save($mac_add);
    //     }
    //     $user_add->profile()->save($profile_add);

    //     //parent_relationship
    //     if ($parent_id){
    //         $spu_add = new spu_relationship([
    //             "parent_id" => $parent_id,
    //             "school_id" => $school_id,
    //         ]);
    //         $user_add->spu_relationship()->save($spu_add);
    //     }
    //     $area = Area::where('school_id',$school_id)->get();
    //     $area_id = $area->map(function ($id) {
    //         return $id->id;
    //     });
    //     $user_add->areas()->attach($area_id);
    //     //存取照片
    //     if ($avatar_file) {
    //         $storageFile = config('services.storage_file');
    //         $storage = config('services.storage');
    //         $timezone = config('services.time_zone');
    //         $current = Carbon::now($timezone)->timestamp;
    //         $avatar_name = "S" . $user_add->id ."-". $current . ".jpg";
    //         $avatar_path = $storageFile. 'avatar/' . $avatar_name;
    //         Storage::disk($storage)->putFileAs($storageFile. 'avatar', $avatar_file, $avatar_name);
    //     }
    //     //get user information
    //     $user = User::find($user_add->id);
    //     if ($student_type == '1') {
    //         if ($avatar_file) {
    //             $user->profile()
    //             ->update([
    //                 'avatar' => $avatar_path,
    //             ]);
    //         }
    //         return $this->succeed('', 200);
    //     } elseif ($student_type == '2' || $student_type == '3') {
    //         //make request to face machine
    //         $client = $this->client();
    //         $response = $client->post('/api/processPerson', [
    //             'json' => [
    //                 "uuid"           => '0', //uuid = null 新增
    //                 "person_name"   => $user->profile->name,
    //                 "person_id"     => "S".$user->id,
    //                 "org_id"       => $user->school_id,
    //                 "person_code"   => "S".$user->id,
    //                 "device_serial"    => $serial_number,
    //                 "face_base64"   => $avatar,
    //                 "face_name"     => "S".$user->id.".jpg",
    //                 "is_active"     => 1
    //             ]
    //         ]);
    //         //get response
    //         $code = $response->getStatusCode(); // 200
    //         $reason = $response->getReasonPhrase(); // OK
    //         $body = json_decode($response->getBody());
    //         // $body = (string) $response->getBody();
    //         if($body->code == 200) {
    //             $user->user_type->uuid()
    //             ->update([
    //                 'uuid' => $body->uuid,
    //             ]);
    //             $user->profile()
    //             ->update([
    //                 'avatar' => $avatar_path,
    //             ]);
    //             return $this->succeed('', 200);
    //         }else {
    //             Storage::disk($storage)->delete('/' . $user->profile->avatar);
    //             $user->user_type()->delete();
    //             $user->profile()->delete();
    //             $user->spu_relationship()->delete();
    //             $user->areas()->detach();
    //             $user->delete();
    //             return $this->errors('fails', $validator->errors(), 401);
    //         }
    //     }

    // }
    public function student_delete(Request $request)
    {
        $id = $request->user_id;
        $admin_id = $request->admin_id;
        //get user information
        $user = User::find($id);
        $school = School::find($user->school_id);
        $student_type = $school->student_type;
        if ($student_type == '1') {
            $user->update([
                'is_actived' => false,
                'department_id' => null,
            ]);
            $storage = config('services.storage');
            Storage::disk($storage)->delete('/avatar/' . $user->profile->avatar);
            Storage::disk($storage)->delete('/avatar/small/' . $user->profile->avatar);
            //relationship kill
            $user->user_type()->delete();
            $user->profile()->update([
                'avatar' => null,
            ]);
            $user->spu_relationship()->delete();
            $user->areas()->detach();
            //log
            if ($admin_id) {
                $this->active_log($admin_id, $user, 'delete', 'success', '刪除學生');
            }
            return $this->succeed('', 200);
        } elseif ($student_type == '2' || $student_type == '3') {
            //持有機台連線確認
            $result = $this->device_check($user->school_id);
            if ($result->getData()->result == true) {
                //連線成功
            } else {
                //連線失敗
                //log
                if ($admin_id) {
                    $this->active_log($admin_id, null, 'delete', 'cannot connect', '刪除學生');
                }
                return $this->error('連線失敗', 400);
            }
            $machines = $school->machines()->select('serial_number', 'base_uri')->get();
            $machines_count =  count($machines);
            $completes = [];
            $count = 1;
            foreach ($machines as $machine) {
                //make request to face machine
                $client = $this->client($machine->base_uri);
                $response = $client->post('/api/processPerson', [
                    'json' => [
                        "uuid"           => $user->user_type->uuid->uuid,
                        "person_name"   => $user->profile->name,
                        "person_id"     => "S" . $user->id,
                        "org_id"       => $user->school_id,
                        "person_code"   => "S" . $user->id,
                        "device_serial"    => $machine->serial_number,
                        "face_base64"   => 'avatar/small/' . $user->profile->avatar,
                        "face_name"     => 'avatar/small/' . $user->profile->avatar,
                        "is_active"     => 0
                    ]
                ]);
                //get response
                $code = $response->getStatusCode(); // 200
                $reason = $response->getReasonPhrase(); // OK
                $body = json_decode($response->getBody());

                if ($body->code == 200) {
                    if ($count == $machines_count) { //為最後一台設備時（前面皆成功）
                        $user = User::find($user->id);
                        $user->update([
                            'is_actived' => false,
                            'department_id' => null,
                        ]);
                        $storage = config('services.storage');
                        Storage::disk($storage)->delete('/avatar/' . $user->profile->avatar);
                        Storage::disk($storage)->delete('/avatar/small/' . $user->profile->avatar);
                        //relationship kill
                        $user->user_type()->delete();
                        $user->profile()->update([
                            'avatar' => null,
                        ]);
                        $user->spu_relationship()->delete();
                        $user->areas()->detach();
                        if ($admin_id) {
                            $this->active_log($admin_id, $user, 'delete', 'success', '刪除學生');
                        }
                        return $this->succeed('', 200);
                    }
                    $count = $count + 1;
                    $complete_uri['base_uri'] = $machine->base_uri; //紀錄已完成機台
                    $completes[] = $machine->serial_number; //紀錄已完成機台
                    continue;
                } elseif ($body->code == 404) { //機台無相同資料
                    if ($count == $machines_count) { //為最後一台設備時（前面皆成功）
                        if ($admin_id) {
                            $this->active_log($admin_id, $user, 'delete', 'success', '刪除學生');
                        }
                        return $this->succeed('', 200);
                    }
                    $count = $count + 1;
                    $complete_uri['base_uri'] = $machine->base_uri; //紀錄已完成機台
                    $completes[] = $machine->serial_number; //紀錄已完成機台
                    continue;
                } else {
                    break;
                }
            }
            if ($admin_id) {
                $this->active_log($admin_id, $user, 'delete', 'faild', '刪除學生');
            }
            return $this->error('刪除問題', 400);
        }
    }
    public function teacher_store(Request $request)
    {
        $school_id = $request->school_id;
        $admin_id = $request->admin_id;
        $name = $request->name;
        $phone = $request->phone;
        //mac -> XX:XX:XX:XX
        $uuid = $request->uuid;
        $mac = $request->mac;
        if ($mac) { //mac存在
            //驗證
            $mac_array = str_split($mac, 2);
            $mac = join(':', $mac_array);
            $validator = Validator::make(
                [
                    'mac' => $mac,
                ],
                [
                    'mac' => 'required|unique:macs',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('MAC重複', $validator->errors(), 401);
            }
        }
        $gender = $request->gender;
        $position_id = $request->position_id;
        $avatar_file = $request->file('avatar_file');
        $avatar = $request->avatar;
        if ($avatar) {
            $avatar  = base64_encode(file_get_contents($avatar));
        }
        $school = School::find($school_id);
        $teacher_type = $school->teacher_type;
        //phone驗證
        $validator = Validator::make(
            [
                'phone' => $phone,
            ],
            [
                'phone' => 'required|unique:users|unique:parents',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('電話重複', $validator->errors(), 401);
        }
        //使用臉部驗證
        if ($teacher_type == '2' || $teacher_type == '3') {
            $validator = Validator::make(
                [
                    'avatar' => $avatar,
                    'avatar_file' => $avatar_file,
                ],
                [
                    'avatar' => 'required',
                    'avatar_file' => 'required',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('相片未上傳', $validator->errors(), 401);
            }
            //持有機台連線確認
            $result = $this->device_check($school_id);
            if ($result->getData()->result == true) {
                //連線成功
            } else {
                //連線失敗
                //log
                if ($admin_id) {
                    $this->active_log($admin_id, null, 'store', 'cannot connect', '新增教師');
                }
                return $this->error('連線失敗', 400);
            }
            $uuid = Str::random(36); //生成uuid
        }

        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $onboard_date = $today;

        //first save to get id
        $user_add = new User([
            'department_id' => null,
            'position_id' => $position_id,
            'onboard_date' => $onboard_date,
            'phone' => $phone,
        ]);
        $user_type_add = new UserType([
            'type_id' => $teacher_type,
        ]);
        $profile_add = new Profile([
            "name" => $name,
            "gender" => $gender,
        ]);
        if ($uuid) {
            $uuid_add = new Uuid([
                'uuid' => $uuid,
            ]);
        }
        if ($mac) {
            $mac_add = new Mac([
                'mac' => $mac,
            ]);
        }
        //save
        $school->users()->save($user_add)->user_type()->save($user_type_add);
        if ($uuid) {
            $user_type_add->uuid()->save($uuid_add);
        }
        if ($mac) {
            $user_type_add->mac()->save($mac_add);
        }
        $user_add->profile()->save($profile_add);

        $area = Area::where('school_id', $school_id)->get();
        $area_id = $area->map(function ($id) {
            return $id->id;
        });
        $user_add->areas()->attach($area_id);
        //存取照片
        if ($avatar_file) {
            $storageFile = config('services.storage_file');
            $storage = config('services.storage');
            $timezone = config('services.time_zone');
            $current = Carbon::now($timezone)->timestamp;
            $avatar_name = "T" . $user_add->id . "-" . $current . ".jpg";
            $avatar_path = $storageFile . 'avatar/' . $avatar_name;
            $avatar_small_path = $storageFile . 'avatar/small/' . $avatar_name;
            Storage::disk($storage)->putFileAs($storageFile . 'avatar', $avatar_file, $avatar_name);
            Storage::disk($storage)->makeDirectory($storageFile . 'avatar/small');
            $this->compressSmallIMG($avatar_path, $avatar_small_path);
        }

        //get user information
        $user = User::find($user_add->id);
        if ($teacher_type == '1') {
            if ($avatar_file) {
                $user->profile()
                    ->update([
                        'avatar' => $avatar_name,
                    ]);
            }
            //log
            if ($admin_id) {
                $this->active_log($admin_id, $user, 'store', 'success', '新增教師');
            }
            $this->FaceScan();
            return $this->succeed('', 200);
        } elseif ($teacher_type == '2' || $teacher_type == '3') {
            $machines = $school->machines()->select('serial_number', 'base_uri')->get();
            $machines_count =  count($machines);
            $completes = [];
            $count = 1;
            foreach ($machines as $machine) {
                //make request to face machine
                $client = $this->client($machine->base_uri);
                $user = User::find($user->id); //刷新user
                $response = $client->post('/api/processPerson', [
                    'json' => [
                        "uuid"           => $count == 1 ? '0' : $user->user_type->uuid->uuid, //uuid = null 新增
                        "person_name"   => $user->profile->name,
                        "person_id"     => "T" . $user->id,
                        "org_id"       => $user->school_id,
                        "person_code"   => "T" . $user->id,
                        "device_serial"    => $machine->serial_number,
                        "face_base64"   => $avatar,
                        "face_name"     => $avatar_name,
                        "is_active"     => 1
                    ]
                ]);
                //get response
                $code = $response->getStatusCode(); // 200
                $reason = $response->getReasonPhrase(); // OK
                $body = json_decode($response->getBody());
                // $body = (string) $response->getBody();
                if ($body->code == 200) {
                    $user->user_type->uuid()
                        ->update([
                            'uuid' => $body->uuid,
                        ]);
                    $user->profile()
                        ->update([
                            'avatar' => $avatar_name,
                        ]);
                    if ($count == $machines_count) {
                        //為最後一筆時
                        //log
                        if ($admin_id) {
                            $this->active_log($admin_id, $user, 'store', 'success', '新增教師');
                        }
                        $this->FaceScan();
                        return $this->succeed('', 200);
                    }
                    $count = $count + 1;
                    $complete_uri['base_uri'] = $machine->base_uri; //紀錄已完成機台
                    $completes[] = $machine->serial_number; //紀錄已完成機台
                    continue;
                } else {
                    if ($count != 1) {
                        //第一筆之後失敗，全部移除
                        $user = User::find($user->id); //刷新user
                        foreach ($completes as $complete) {
                            $client = $this->client($complete_uri['base_uri']);
                            $response = $client->post('/api/processPerson', [
                                'json' => [
                                    "uuid"           => $user->user_type->uuid->uuid,
                                    "person_name"   => $user->profile->name,
                                    "person_id"     => "T" . $user->id,
                                    "org_id"       => $user->school_id,
                                    "person_code"   => "T" . $user->id,
                                    "device_serial"    => $complete, //已完成的serial_number
                                    "face_base64"   => 'avatar/small/' . $user->profile->avatar,
                                    "face_name"     => 'avatar/small/' . $user->profile->avatar,
                                    "is_active"     => 0
                                ]
                            ]);
                        }
                    }
                    $storage = config('services.storage');
                    Storage::disk($storage)->delete('/avatar/' . $user->profile->avatar);
                    Storage::disk($storage)->delete('/avatar/small/' . $user->profile->avatar);
                    $user->user_type()->delete();
                    $user->profile()->delete();
                    $user->areas()->detach();
                    $user->delete();
                    break;
                }
            }
            //log
            if ($admin_id) {
                $this->active_log($admin_id, $user, 'store', 'faild', '新增教師');
            }
            return $this->errors('新增失敗', $validator->errors(), 401);
        }
    }
    public function teacher_delete(Request $request)
    {
        $id = $request->teacher_id;
        $admin_id = $request->admin_id;
        //get user information
        $user = User::find($id);
        $school = School::find($user->school_id);
        $teacher_type = $school->teacher_type;
        $serial_number = $school->machines()->first()->serial_number;
        //
        $depart_check = $user->departments()->get();
        if (!$depart_check->isEmpty()) {
            return $this->error('仍有負責班級，請先移除', 400);
        }
        if ($teacher_type == '1') {
            $user->update([
                'is_actived' => false,
                'department_id' => null,
                'phone' => null,
            ]);
            $storage = config('services.storage');
            Storage::disk($storage)->delete('/avatar/' . $user->profile->avatar);
            Storage::disk($storage)->delete('/avatar/small/' . $user->profile->avatar);
            //relationship kill
            $user->user_type()->delete();
            $user->profile()->update([
                'avatar' => null,
            ]);
            $user->areas()->detach();
            //log
            if ($admin_id) {
                $this->active_log($admin_id, $user, 'delete', 'success', '刪除教師');
            }
            return $this->succeed('', 200);
        } elseif ($teacher_type == '2' || $teacher_type == '3') {
            //持有機台連線確認
            $result = $this->device_check($user->school_id);
            if ($result->getData()->result == true) {
                //連線成功
            } else {
                //連線失敗
                //log
                if ($admin_id) {
                    $this->active_log($admin_id, null, 'delete', 'cannot connect', '刪除教師');
                }
                return $this->error('連線失敗', 400);
            }
            $machines = $school->machines()->select('serial_number', 'base_uri')->get();
            $machines_count =  count($machines);
            $completes = [];
            $count = 1;
            foreach ($machines as $machine) {
                //make request to face machine
                $client = $this->client($machine->base_uri);
                $response = $client->post('/api/processPerson', [
                    'json' => [
                        "uuid"           => $user->user_type->uuid->uuid,
                        "person_name"   => $user->profile->name,
                        "person_id"     => "T" . $user->id,
                        "org_id"       => $user->school_id,
                        "person_code"   => "T" . $user->id,
                        "device_serial"    => $machine->serial_number,
                        "face_base64"   => 'avatar/small/' . $user->profile->avatar,
                        "face_name"     => 'avatar/small/' . $user->profile->avatar,
                        "is_active"     => 0
                    ]
                ]);
                //get response
                $code = $response->getStatusCode(); // 200
                $reason = $response->getReasonPhrase(); // OK
                $body = json_decode($response->getBody());

                if ($body->code == 200) {
                    if ($count == $machines_count) { //為最後一台設備時（前面皆成功）
                        $user = User::find($user->id);
                        $user->update([
                            'is_actived' => false,
                            'department_id' => null,
                            'phone' => null,
                        ]);
                        $storage = config('services.storage');
                        Storage::disk($storage)->delete('/avatar/' . $user->profile->avatar);
                        Storage::disk($storage)->delete('/avatar/small/' . $user->profile->avatar);
                        //relationship kill
                        $user->user_type()->delete();
                        $user->profile()->update([
                            'avatar' => null,
                        ]);
                        $user->areas()->detach();
                        if ($admin_id) {
                            $this->active_log($admin_id, $user, 'delete', 'success', '刪除教師');
                        }
                        return $this->succeed('', 200);
                    }
                    $count = $count + 1;
                    $complete_uri['base_uri'] = $machine->base_uri; //紀錄已完成機台
                    $completes[] = $machine->serial_number; //紀錄已完成機台
                    continue;
                } elseif ($body->code == 404) { //機台無相同資料
                    if ($count == $machines_count) { //為最後一台設備時（前面皆成功）
                        if ($admin_id) {
                            $this->active_log($admin_id, $user, 'delete', 'success', '刪除教師');
                        }
                        return $this->succeed('', 200);
                    }
                    $count = $count + 1;
                    $complete_uri['base_uri'] = $machine->base_uri; //紀錄已完成機台
                    $completes[] = $machine->serial_number; //紀錄已完成機台
                    continue;
                } else {
                    break;
                }
            }
            if ($admin_id) {
                $this->active_log($admin_id, $user, 'delete', 'faild', '刪除教師');
            }
            return $this->error('刪除問題', 400);
        }
    }

    public function student_store(Request $request)
    {
        $school_id = $request->school_id;
        $admin_id = $request->admin_id;
        $name = $request->name;
        //mac -> XX:XX:XX:XX
        $uuid = $request->uuid;
        $mac = $request->mac;
        if ($mac) { //mac存在
            //驗證
            $mac_array = str_split($mac, 2);
            $mac = join(':', $mac_array);
            $validator = Validator::make(
                [
                    'mac' => $mac,
                ],
                [
                    'mac' => 'required|unique:macs',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('MAC重複', $validator->errors(), 401);
            }
        }
        $gender = $request->gender;
        $avatar_file = $request->file('avatar_file');
        $avatar = $request->avatar;
        if ($avatar) {
            $avatar  = base64_encode(file_get_contents($avatar));
        }
        $school = School::find($school_id);
        $student_type = $school->student_type;
        //可有可無
        $department_id = $request->department_id;
        $parent_id = $request->parent_id;
        //使用臉部驗證
        if ($student_type == '2' || $student_type == '3') {
            $validator = Validator::make(
                [
                    'avatar' => $avatar,
                    'avatar_file' => $avatar_file,
                ],
                [
                    'avatar' => 'required',
                    'avatar_file' => 'required',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('相片為未上傳', $validator->errors(), 401);
            }
            //持有機台連線確認
            $result = $this->device_check($school_id);
            if ($result->getData()->result == true) {
                //連線成功
            } else {
                //連線失敗
                //log
                if ($admin_id) {
                    $this->active_log($admin_id, null, 'store', 'cannot connect', '新增學生');
                }
                return $this->error('連線失敗', 400);
            }
            $uuid = Str::random(36); //生成uuid
        }


        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $onboard_date = $today;

        //first save to get id
        $user_add = new User([
            'department_id' => $department_id ? $department_id : null,
            'position_id' => 10,
            'onboard_date' => $onboard_date,
        ]);
        $user_type_add = new UserType([
            'type_id' => $student_type,
        ]);
        $profile_add = new Profile([
            "name" => $name,
            "gender" => $gender,
        ]);
        if ($uuid) {
            $uuid_add = new Uuid([
                'uuid' => $uuid,
            ]);
        }
        if ($mac) {
            $mac_add = new Mac([
                'mac' => $mac,
            ]);
        }
        //save
        $school->users()->save($user_add)->user_type()->save($user_type_add);
        if ($uuid) {
            $user_type_add->uuid()->save($uuid_add);
        }
        if ($mac) {
            $user_type_add->mac()->save($mac_add);
        }
        $user_add->profile()->save($profile_add);

        //parent_relationship
        if ($parent_id) {
            $spu_add = new spu_relationship([
                "parent_id" => $parent_id,
                "school_id" => $school_id,
            ]);
            $user_add->spu_relationship()->save($spu_add);
        }
        $area = Area::where('school_id', $school_id)->get();
        $area_id = $area->map(function ($id) {
            return $id->id;
        });
        $user_add->areas()->attach($area_id);
        //存取照片
        if ($avatar_file) {
            $storageFile = config('services.storage_file');
            $storage = config('services.storage');
            $timezone = config('services.time_zone');
            $current = Carbon::now($timezone)->timestamp;
            $avatar_name = "S" . $user_add->id . "-" . $current . ".jpg";
            $avatar_path = $storageFile . 'avatar/' . $avatar_name;
            $avatar_small_path = $storageFile . 'avatar/small/' . $avatar_name;
            Storage::disk($storage)->putFileAs($storageFile . 'avatar', $avatar_file, $avatar_name);
            Storage::disk($storage)->makeDirectory($storageFile . 'avatar/small');
            $this->compressSmallIMG($avatar_path, $avatar_small_path);
        }
        //get user information
        $user = User::find($user_add->id);
        if ($student_type == '1') {
            if ($avatar_file) {
                $user->profile()
                    ->update([
                        'avatar' => $avatar_name,
                    ]);
            }
            //log
            if ($admin_id) {
                $this->active_log($admin_id, $user, 'store', 'success', '新增學生');
            }
            $this->FaceScan();
            return $this->succeed('', 200);
        } elseif ($student_type == '2' || $student_type == '3') {
            $machines = $school->machines()->select('serial_number', 'base_uri')->get();
            $machines_count =  count($machines);
            $completes = [];
            $count = 1;
            foreach ($machines as $machine) {
                //make request to face machine
                $client = $this->client($machine->base_uri);
                $user = User::find($user->id); //刷新user
                $response = $client->post('/api/processPerson', [
                    'json' => [
                        "uuid"           => $count == 1 ? '0' : $user->user_type->uuid->uuid, //uuid = null 新增
                        "person_name"   => $user->profile->name,
                        "person_id"     => "S" . $user->id,
                        "org_id"       => $user->school_id,
                        "person_code"   => "S" . $user->id,
                        "device_serial"    => $machine->serial_number,
                        "face_base64"   => $avatar,
                        "face_name"     => $avatar_name,
                        "is_active"     => 1
                    ]
                ]);

                //get response
                $code = $response->getStatusCode(); // 200
                $reason = $response->getReasonPhrase(); // OK
                $body = json_decode($response->getBody());
                if ($body->code == 200) {
                    $user->user_type->uuid()
                        ->update([
                            'uuid' => $body->uuid,
                        ]);
                    $user->profile()
                        ->update([
                            'avatar' => $avatar_name,
                        ]);
                    if ($count == $machines_count) {
                        //為最後一筆時
                        //log
                        if ($admin_id) {
                            $this->active_log($admin_id, $user, 'store', 'success', '新增學生');
                        }
                        $this->FaceScan();
                        return $this->succeed('', 200);
                    }
                    $count = $count + 1;
                    $complete_uri['base_uri'] = $machine->base_uri; //紀錄已完成機台
                    $completes[] = $machine->serial_number; //紀錄已完成機台
                    continue;
                } else {
                    if ($count != 1) {
                        //第一筆之後失敗，全部移除
                        $user = User::find($user->id); //刷新user
                        foreach ($completes as $complete) {
                            $client = $this->client($complete_uri['base_uri']);
                            $response = $client->post('/api/processPerson', [
                                'json' => [
                                    "uuid"           => $user->user_type->uuid->uuid,
                                    "person_name"   => $user->profile->name,
                                    "person_id"     => "S" . $user->id,
                                    "org_id"       => $user->school_id,
                                    "person_code"   => "S" . $user->id,
                                    "device_serial"    => $complete, //已完成的serial_number
                                    "face_base64"   => 'avatar/small/' . $user->profile->avatar,
                                    "face_name"     => "S" . $user->id . ".jpg",
                                    "is_active"     => 0
                                ]
                            ]);
                        }
                    }
                    $storage = config('services.storage');
                    Storage::disk($storage)->delete('/avatar/' . $user->profile->avatar);
                    Storage::disk($storage)->delete('/avatar/small/' . $user->profile->avatar);
                    $user->user_type()->delete();
                    $user->profile()->delete();
                    $user->spu_relationship()->delete();
                    $user->areas()->detach();
                    $user->delete();
                    break;
                }
            }
            //log
            if ($admin_id) {
                $this->active_log($admin_id, $user, 'store', 'faild', '新增學生');
            }
            return $this->errors('新增失敗', $validator->errors(), 401);
        }
    }

    public function student_update($school_id, $user, $avatar, $avatar_file, $current)
    {
        $school = School::find($school_id);

        $machines = $school->machines()->select('serial_number', 'base_uri')->get();
        $machines_count =  count($machines);
        $completes = [];
        $count = 1;
        foreach ($machines as $machine) {
            //make request to face machine
            $client = $this->client($machine->base_uri);
            $response = $client->post('/api/processPerson', [
                'json' => [
                    "uuid"           => $user->user_type->uuid->uuid,
                    "person_name"   => $user->profile->name,
                    "person_id"     => "S" . $user->id,
                    "org_id"       => $user->school_id,
                    "person_code"   => "S" . $user->id,
                    "device_serial"    => $machine->serial_number,
                    "face_base64"   => $avatar,
                    "face_name"     => "S" . $user->id . "-" . $current . ".jpg",
                    "is_active"     => 1
                ]
            ]);
            //get response
            $code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK
            $body = json_decode($response->getBody());

            if ($body->code == 200) {
                if ($count == $machines_count) { //為最後一台設備時（前面皆成功）
                    if ($avatar_file) {
                        $avatar_name = $this->student_file_update($user, $avatar_file, $current);
                        $user->profile()
                            ->update([
                                'avatar' => $avatar_name,
                            ]);
                    }
                    $this->FaceScan();
                    return $this->succeed('', 200);
                }
                $count = $count + 1;
                $complete_uri['base_uri'] = $machine->base_uri; //紀錄已完成機台
                $completes[] = $machine->serial_number; //紀錄已完成機台
                continue;
            } else {
                if ($count != 1) {
                    //第二筆之後失敗，前面設備重置avatar
                    //取得舊avatar_base64
                    $old_avatar = 'avatar/small/' . $user->profile->avatar;
                    $old_avatar = base64_encode(file_get_contents($old_avatar));
                    $completes;
                    foreach ($completes as $complete) {
                        $client = $this->client($complete_uri['base_uri']);
                        $response = $client->post('/api/processPerson', [
                            'json' => [
                                "uuid"           => $user->user_type->uuid->uuid,
                                "person_name"   => $user->profile->name,
                                "person_id"     => "S" . $user->id,
                                "org_id"       => $user->school_id,
                                "person_code"   => "S" . $user->id,
                                "device_serial"    => $machine->serial_number,
                                "face_base64"   => $old_avatar,
                                "face_name"     => "S" . $user->id . "-" . $current . ".jpg",
                                "is_active"     => 1
                            ]
                        ]);
                    }
                }
                break;
            }
        }
        return $this->error('更新問題', 400);
    }
    public function machines_list(Request $request)
    {
        $school_id = $request->school_id;
        $machines = Machine::where('school_id', $school_id)->get();
        return $machines;
    }
    public function machines_sync(Request $request)
    {
        $origin_machine = $request->origin_machine;
        $copy_machine = $request->copy_machine_array;

        $machine = Machine::where('serial_number', $origin_machine)->select('base_uri')->first();
        $client = $this->client($machine->base_uri);
        $response = $client->post('/api/devices/sync', [
            'json' => [
                "device_id_from" => $origin_machine,
                "device_id_to" => $copy_machine[0]
            ],
            'progress' => function (
                $downloadTotal,
                $downloadedBytes,
                $uploadTotal,
                $uploadedBytes
            ) {
                echo ($downloadTotal . '*');
            },
        ]);
        //get response
        $code = $response->getStatusCode(); // 200
        $reason = $response->getReasonPhrase(); // OK
        $body = json_decode($response->getBody());
        if ($code == 200) {
            return 1;
            // return $this->succeed('', 200);
        } else {
            return 2;
            // return $this->error('異常', $code);
        }


        // [
        //     'progress' => function(
        //         $downloadTotal,
        //         $downloadedBytes,
        //         $uploadTotal,
        //         $uploadedBytes
        //     ) {
        //         //do something
        //     },
        // ]
    }
    public function FaceScan()
    {
        //加入FaceScan隊列
        $job = (new FaceScan())->onConnection('redis');
        $this->dispatch($job);
    }
    public function testFaceScan(Request $request)
    {
        //加入FaceScan隊列
        $job = (new FaceScan())->onConnection('redis');
        $this->dispatch($job);
    }
}
