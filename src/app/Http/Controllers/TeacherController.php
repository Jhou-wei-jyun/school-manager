<?php

namespace App\Http\Controllers;

use App\API\ApiHelper;
use App\Http\Controllers\Controller;

use App\Admin;
use App\Area;
use App\User;
use App\UserType;
use App\Mac;
use App\Profile;
use App\School;
use App\spu_relationship;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    use ApiHelper;

    public function beconteacher(Request $request)
    {

        $school_id = $request->school_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');

        $teachers = School::find($school_id)
            ->users()
            ->whereNotIn('position_id', [10, 20])->where('is_actived', true)
            ->with('user_type.mac')
            ->with('department:id,name')
            ->with(['records' => function ($recordQuery) use ($today) {
                $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
            }])
            ->get();

        $teachers = $teachers->map(function ($teacher) {

            $collection =  collect([
                'id' => $teacher->id,
                'name' => $teacher->profile->name,
                'avatar' => 'avatar/small/' . $teacher->profile->avatar,
                'gender' => $teacher->profile->gender,
                'mac' => $teacher->user_type->mac == null ? null : $teacher->user_type->mac->mac,
                'battery' => $teacher->records->last()['battery'],
                'onboard_date' => $teacher->onboard_date,
                'phone' => $teacher->phone,
            ]);

            return $collection;
        });

        return $teachers;
    }
    public function index_becon(Request $request)
    {
        $school_id = $request->school_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $teachers = School::find($school_id)
            ->users()
            ->whereNotIn('position_id', [10, 20])->where('is_actived', true)
            ->with('user_type.mac')
            ->with('departments:supervisor_id,id,name')
            ->with(['records' => function ($recordQuery) use ($today) {
                $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
            }])
            ->get();

        $teachers = $teachers->map(function ($teacher) {

            $collection =  collect([
                'id' => $teacher->id,
                'avatar' => 'avatar/small/' . $teacher->profile->avatar,
                'name' => $teacher->profile->name,
                'gender' => $teacher->profile->gender,
                'phone' => $teacher->phone,
                'mac' => $teacher->user_type->mac == null ? null : $teacher->user_type->mac->mac,
                'department' => $teacher->departments,
                'date_time' => $teacher->records->first()['date_time'],
                'leave_at' => $teacher->records->last()['leave_at'],
            ]);

            return $collection;
        });

        return $teachers;
    }
    public function index_face(Request $request)
    {
        $school_id = $request->school_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $teachers = School::find($school_id)
            ->users()
            ->whereNotIn('position_id', [10, 20])->where('is_actived', true)
            ->with('user_type.uuid')
            ->with('departments:supervisor_id,id,name')
            ->with(['tempers' => function ($temperQuery) use ($today) {
                $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'desc');
            }])
            ->get();

        $teachers = $teachers->map(function ($teacher) {

            $collection =  collect([
                'id' => $teacher->id,
                'avatar' => 'avatar/small/' . $teacher->profile->avatar,
                'name' => $teacher->profile->name,
                'gender' => $teacher->profile->gender,
                'phone' => $teacher->phone,
                'uuid' => $teacher->user_type->uuid == null ? null : $teacher->user_type->uuid->uuid,
                'department' => $teacher->departments,
                'tempers' => $teacher->tempers->first()['temperature_val'],
                'date_time' => $teacher->tempers->last()['record_time'],
                'leave_at' => $teacher->tempers->first()['record_time'],
            ]);

            return $collection;
        });

        return $teachers;
    }
    public function index_becon_face(Request $request)
    {
        $school_id = $request->school_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $teachers = School::find($school_id)
            ->users()
            ->whereNotIn('position_id', [10, 20])->where('is_actived', true)
            ->with('user_type.mac')
            ->with('departments:supervisor_id,id,name')
            ->with(['records' => function ($recordQuery) use ($today) {
                $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
            }])
            ->with(['tempers' => function ($temperQuery) use ($today) {
                $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'desc');
            }])
            ->get();
        $teachers = $teachers->map(function ($teacher) {

            $collection =  collect([
                'id' => $teacher->id,
                'avatar' => 'avatar/small/' . $teacher->profile->avatar,
                'name' => $teacher->profile->name,
                'gender' => $teacher->profile->gender,
                'phone' => $teacher->phone,
                'mac' => $teacher->user_type->mac == null ? null : $teacher->user_type->mac->mac,
                'department' => $teacher->departments,
                'tempers' => $teacher->tempers->first()['temperature_val'],
                'date_time' => $teacher->records->first()['date_time'],
                'leave_at' => $teacher->records->last()['leave_at'],
            ]);

            return $collection;
        });

        return $teachers;
    }
    public function store_becon(Request $request)
    {
        $name = $request->name;
        $phone = $request->phone;
        //mac -> XX:XX:XX:XX
        $mac = $request->mac;
        $mac_array = str_split($mac, 2);
        $mac = join(':', $mac_array);
        //baee64 encode
        $avatar = $request->avatar;
        $avatar_file = $request->file('avatar_file');
        if ($avatar) {
            $avatar  = base64_encode(file_get_contents($avatar));
        }

        $gender = $request->gender;
        $position_id = $request->position_id;
        $onboard_date = $request->start_date;
        $school_id = $request->school_id;
        //驗證
        $validator = Validator::make(
            [
                'phone' => $phone,
                'mac' => $mac,
            ],
            [
                'phone' => 'required|unique:users|unique:parents',
                'mac' => 'required|unique:macs',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('電話或MAC重複', $validator->errors(), 401);
        }

        $user_add = new User([
            'department_id' => null,
            'position_id' => $position_id,
            'onboard_date' => $onboard_date,
            'phone' => $phone,
        ]);
        $user_type_add = new UserType([
            'type_id' => 1, //type_id = 1 = becon
        ]);
        $profile_add = new Profile([
            "name" => $name,
            "avatar" => $avatar,
            "gender" => $gender,
        ]);
        $mac_add = new Mac([
            'mac' => $mac,
        ]);
        $school = School::find($school_id);
        //save
        $school->users()->save($user_add)->user_type()->save($user_type_add)->mac()->save($mac_add);
        $user_add->profile()->save($profile_add);

        $area = Area::where('school_id', $school_id)->get();
        $area_id = $area->map(function ($id) {
            return $id->id;
        });
        $user_add->areas()->attach($area_id);

        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        $timezone = config('services.time_zone');
        $current = Carbon::now($timezone)->timestamp;
        $avatar_name = "T" . $user_add->id . "-" . $current . ".jpg";
        $avatar_path = $storageFile . 'avatar/' . $avatar_name;
        Storage::disk($storage)->putFileAs($storageFile . 'avatar', $avatar_file, $avatar_name);

        $user = User::find($user_add->id);
        $user->profile()
            ->update([
                'avatar' => $avatar_path,
            ]);
        return $this->succeed('', 200);
    }
    public function update_info(Request $request)
    {
        $id = $request->teacher_id;
        $admin_id = (int) $request->admin_id;
        $name = $request->name;
        $gender = $request->gender;
        $phone = $request->phone;
        $mac = $request->mac;
        if ($mac) {
            $mac_array = str_split($mac, 2);
            $mac = join(':', $mac_array);
        }

        $user = User::find($id);
        //驗證 Phone 有無更動
        if ($user->phone != $phone) {
            //有，驗證其他有無重複
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
        }

        if ($mac) { //有無Mac
            //驗證有無更動
            if ($user->user_type->mac->mac != $mac) {
                //有，驗證其他有無重複
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
                //Mac 無重複，更新
                $user->user_type->mac()
                    ->update([
                        'mac' => $mac,
                    ]);
            }
        }

        //Phone 無重複，更新
        $user->update([
            'phone' => $phone,
        ]);
        $user->profile()
            ->update([
                'name' => $name,
                'gender' => $gender,
            ]);
        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($user)
                ->withProperties([
                    'type' => 'edit',
                    'result' => 'success',
                ])
                ->log('編輯教師資訊');
        }
        return $this->succeed('', 200);
    }

    // public function delete_becon(Request $request)
    // {
    //     $id = $request->teacher_id;
    //     $user = User::find($id);

    //     $depart_check = $user->departments()->get();
    //     if(!$depart_check->isEmpty()){
    //         return $this->error('仍有負責班級，請先移除', 400);
    //     }

    //     $user->update([
    //         'is_actived' => false,
    //         'department_id' => null,
    //         'phone' => null,
    //     ]);
    //     $storage = config('services.storage');
    //     Storage::disk($storage)->delete('/' . $user->profile->avatar);
    //     //relationship kill
    //     $user->user_type()->delete();
    //     $user->profile()->delete();
    //     $user->areas()->detach();

    //     return $this->succeed('',200);
    // }
    public function student_school_type($user_id)
    {
        $student_type = User::find($user_id)->school->student_type;

        return $student_type;
    }
}
