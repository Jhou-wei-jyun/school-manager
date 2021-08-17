<?php

namespace App\Http\Controllers;

use App\API\ApiHelper;
use App\Http\Controllers\Controller;

use App\Admin;
use App\Area;
use App\Chat_message;
use App\User;
use App\Department;
use App\UserType;
use App\Mac;
use App\Profile;
use App\School;
use App\spu_relationship;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    use ApiHelper;

    public function student_school_type($user_id)
    {
        $student_type = User::find($user_id)->school->student_type;

        return $student_type;
    }
    public function index_becon(Request $request)
    {
        $school_id = $request->school_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $students = School::find($school_id)
            ->users()
            ->where("position_id", 10)->where('is_actived', true)
            ->with('user_type.mac')
            ->with('department:id,name,start_at,finish_at')
            ->with(['records' => function ($recordQuery) use ($today) {
                $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
            }])
            ->get();

        $students = $students->map(function ($student) {

            $collection =  collect([
                'id' => $student->id,
                'avatar' => $student->profile->avatar ? null : 'avatar/small/' . $student->profile->avatar,
                'name' => $student->profile->name,
                'gender' => $student->profile->gender,
                'mac' => $student->user_type->mac == null ? null : $student->user_type->mac->mac,
                'department' => $student->department == null ? null : $student->department->name,
                'start' => $student->department == null ? null : $student->department->start_at,
                'end' => $student->department == null ? null : $student->department->finish_at,
                'date_time' => $student->records->first()['date_time'],
                'leave_at' => $student->records->last()['leave_at'],
            ]);

            return $collection;
        });

        return $students;
    }
    public function index_face(Request $request)
    {
        $school_id = $request->school_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $students = School::find($school_id)
            ->users()
            ->where("position_id", 10)->where('is_actived', true)
            ->with('user_type.uuid')
            ->with('department:id,name,start_at,finish_at')
            ->with(['tempers' => function ($temperQuery) use ($today) {
                $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'desc');
            }])
            ->with(['leaves' => function ($leaveQuery) use ($today) {
                $leaveQuery->where('leaves.created_at', 'like', '%' . $today . '%')->orderBy('id', 'desc');
            }])
            ->get();

        $students = $students->map(function ($student) {

            $collection =  collect([
                'id' => $student->id,
                'avatar' => $student->profile->avatar ? null : 'avatar/small/' . $student->profile->avatar,
                'name' => $student->profile->name,
                'gender' => $student->profile->gender,
                'uuid' => $student->user_type->uuid == null ? null : $student->user_type->uuid->uuid,
                'department' => $student->department == null ? null : $student->department->name,
                'start' => $student->department == null ? null : $student->department->start_at,
                'end' => $student->department == null ? null : $student->department->finish_at,
                'tempers' => $student->tempers->first()['temperature_val'],
                'date_time' => $student->tempers->last()['record_time'],
                'leave_at' => $student->tempers->last()['record_time'] ? $student->leaves->last()['updated_at'] : null,
            ]);

            return $collection;
        });

        return $students;
    }
    public function index_becon_face(Request $request)
    {
        $school_id = $request->school_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $students = School::find($school_id)
            ->users()
            ->where("position_id", 10)->where('is_actived', true)
            ->with('user_type.mac')
            ->with('department:id,name,start_at,finish_at')
            ->with(['records' => function ($recordQuery) use ($today) {
                $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
            }])
            ->with(['tempers' => function ($temperQuery) use ($today) {
                $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'desc');
            }])
            ->get();

        $students = $students->map(function ($student) {

            $collection =  collect([
                'id' => $student->id,
                'avatar' => $student->profile->avatar ? null : 'avatar/small/' . $student->profile->avatar,
                'name' => $student->profile->name,
                'gender' => $student->profile->gender,
                'mac' => $student->user_type->mac == null ? null : $student->user_type->mac->mac,
                'department' => $student->department == null ? null : $student->department->name,
                'start' => $student->department == null ? null : $student->department->start_at,
                'end' => $student->department == null ? null : $student->department->finish_at,
                'tempers' => $student->tempers->first()['temperature_val'],
                'date_time' => $student->records->first()['date_time'],
                'leave_at' => $student->records->last()['leave_at'],
            ]);

            return $collection;
        });

        return $students;
    }
    public function beconstudent(Request $request)
    {
        $school_id = $request->school_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');

        $students = School::find($school_id)
            ->users()
            ->where("position_id", 10)->where('is_actived', true)
            ->with('user_type.mac')
            ->with('department:id,name')
            ->with('spu_relationship.parent')
            ->with(['records' => function ($recordQuery) use ($today) {
                $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
            }])
            ->get();

        $students = $students->map(function ($student) {

            $collection =  collect([
                'id' => $student->id,
                'name' => $student->profile->name,
                'avatar' => 'avatar/small/' . $student->profile->avatar,
                'gender' => $student->profile->gender,
                'mac' => $student->user_type->mac == null ? null : $student->user_type->mac->mac,
                'battery' => $student->records->last()['battery'],
                'onboard_date' => $student->onboard_date,
                'parent' => $student->spu_relationship == null ? null : $student->spu_relationship->parent->name,
                'parent_phone' => $student->spu_relationship == null ? null : $student->spu_relationship->parent->phone,
            ]);

            return $collection;
        });

        return $students;
    }
    public function update_info(Request $request)
    {
        $id = $request->user_id;
        $admin_id = (int) $request->admin_id;
        $name = $request->name;
        $gender = $request->gender;
        $mac = $request->mac;
        if ($mac) {
            $mac_array = str_split($mac, 2);
            $mac = join(':', $mac_array);
        }

        $user = User::find($id);
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
                ->log('編輯學生資訊');
        }
        return $this->succeed('', 200);
    }

    // public function delete_becon(Request $request)
    // {
    //     $id = $request->user_id;
    //     $user = User::find($id);
    //     $user->update([
    //         'is_actived' => false,
    //         'department_id' => null,
    //         ]);
    //     $storage = config('services.storage');
    //     Storage::disk($storage)->delete('/' . $user->profile->avatar);
    //     //relationship kill
    //     $user->user_type()->delete();
    //     $user->profile()->delete();
    //     $user->spu_relationship()->delete();
    //     $user->areas()->detach();

    //     return $this->succeed('',200);
    // }
    public function department_relationship_change(Request $request)
    {
        $admin_id = (int) $request->admin_id;
        $school_id =  $request->school_id;
        $department_id =  $request->department_id;
        $pair_id = $request->pair_id;
        $all_id = $request->all_id;
        $user_id_list = [];
        if ($pair_id !== null) {
            $pieces = explode(",", $pair_id);
            for ($i = 0; $i < count($pieces); $i++) {
                $user_id_list[] = (int) $pieces[$i];
            }
        }

        $teacher_id = Department::find($department_id)->supervisor_id;
        $ori_user_id_list = User::where('department_id', $department_id)->pluck('id')->toArray();
        $user_id_diff = array_diff($user_id_list, $ori_user_id_list); //新增的id
        // return $user_id_diff;

        // return $recode_notifies;
        User::whereIn('id', $ori_user_id_list)->update(['department_id' => null]);
        User::whereIn('id', $user_id_list)->update(['department_id' => $department_id]);
        foreach ($user_id_list as $user_id) {
            foreach ($user_id_diff as $id_diff) {
                if (in_array($user_id, $user_id_diff)) {
                    $recode_notifies = User::where('school_id', $school_id) //新增的id以前的推播紀錄
                        ->with(['notifies' => function ($query) use ($id_diff) {
                            $query->where('student_id', $id_diff);
                        }])
                        ->get()->pluck('notifies')->collapse()->unique('id');
                    foreach ($recode_notifies as $recode_notify) {
                        $recode_notify->users()->sync([$teacher_id => ['status' => 0, 'student_id' => $id_diff]]);
                    }
                    // Chat_message::where('user_id', $user_id) //新增的id以前的聊天紀錄
                    //     ->update([
                    //         'teacher_id' => $teacher_id
                    //     ]);
                }
            }
        }

        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->withProperties([
                    'type' => 'edit',
                    'result' => 'success',
                    'target' => $pair_id,
                ])
                ->log('編輯學生班級關聯');
        }
        return $this->succeed($request, 200);
    }
    public function department_all_student_list(Request $request)
    {
        $department_id = $request->department_id;
        $school_id = $request->school_id;

        // 已配對的
        $paired = School::find($school_id)->users()
            ->where('position_id', 10)
            ->where('is_actived', true)
            ->where('department_id', $department_id)
            ->with('profile')
            ->get();
        $paired = $paired->map(function ($pair) {

            $collection =  collect([
                'id' => $pair->id,
                'name' => $pair->profile->name,
            ]);

            return $collection;
        });
        // 未配對的小孩
        $notpaired = School::find($school_id)->users()
            ->where('position_id', 10)
            ->where('is_actived', true)
            ->where('department_id', null)
            ->get();
        $notpaired = $notpaired->map(function ($notpair) {

            $collection =  collect([
                'id' => $notpair->id,
                'name' => $notpair->profile->name,
            ]);

            return $collection;
        });
        //全小孩清單
        $all_student = array_sort_recursive(array_collapse([$notpaired, $paired]));
        return $all_student;
    }
    public function department_pair_student_list(Request $request)
    {
        $department_id = $request->department_id;
        $school_id = $request->school_id;

        // 已配對的
        $paired = School::find($school_id)->users()
            ->where('position_id', 10)
            ->where('is_actived', true)
            ->where('department_id', $department_id)
            ->with('profile')
            ->get();
        $paired = $paired->map(function ($pair) {

            $collection =  collect([
                'id' => $pair->id,
                'name' => $pair->profile->name,
            ]);

            return $collection;
        });
        return $paired;
    }
}
