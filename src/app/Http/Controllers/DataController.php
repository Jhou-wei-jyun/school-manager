<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\FCMNotification;

use App\School;
use App\Admin;
use App\User;
use App\Parents;
use App\spu_relationship;
use App\Temperature;
use App\Department;
use App\Record;
use App\API\ApiHelper;
use Carbon\Carbon;

class DataController extends Controller
{
    use ApiHelper;

    public function student_school_type($school_id)
    {
        $student_type = School::find($school_id)->student_type;

        return $student_type;
    }
    public function record_tempers($student_type)
    {
        if ($student_type == '1' || $student_type == '3') {
            return 'records';
        } else if ($student_type == '2') {
            return 'tempers';
        }
    }
    public function date_record($student_type)
    {
        if ($student_type == '1' || $student_type == '3') {
            return 'date_time';
        } else if ($student_type == '2') {
            return 'record_time';
        }
    }
    public function getErrTemp(Request $request)
    {
        $school_id = $request->school_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');

        $errorTemps = User::where('school_id', $school_id)
            ->where('position_id', 10)
            ->where('is_actived', 1)
            ->with('profile')
            ->with(['tempers' => function ($temperQuery) use ($today) {
                $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'desc');
            }])
            ->get();
        $errorTemps = $errorTemps->map(function ($errorTemp) {
            $collection =  collect([
                'id' => $errorTemp->tempers->first()['id'],
                'name' => $errorTemp->profile->name,
                'temperature_val' => $errorTemp->tempers->first()['temperature_val'],
                'user_id' => $errorTemp->tempers->first()['user_id'],
                'check' => $errorTemp->tempers->first()['check'],
            ]);
            return $collection;
        })->filter(function ($errorTemp) {
            return ((float) $errorTemp['temperature_val']) >= 37.5;
        });
        $errorTemps_count = $errorTemps->count();
        return response()->json(['data' => $errorTemps, 'count' => $errorTemps_count]);
    }
    public function getTeacher(Request $request)
    {
        $school_id = $request->school_id;
        // return $school_id;

        $teacherCount = User::where('school_id', $school_id)
            ->whereNotIn('position_id', [10, 20])
            ->where('is_actived', 1)
            ->count();
        return $teacherCount;
    }
    public function getStudent(Request $request)
    {
        $school_id = $request->school_id;
        $studentCount = User::where('school_id', $school_id)
            ->where('position_id', 10)
            ->where('is_actived', 1)
            ->count();
        return $studentCount;
    }
    public function getlateStudent(Request $request)
    {
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        // $today = '2020-10-19';
        // $late = Carbon::parse('10:00');
        $school_id = $request->school_id;
        $student_type = $this->student_school_type($school_id);
        $table_str = $this->record_tempers($student_type);
        $time_str = $this->date_record($student_type);

        if ($student_type == '1' || $student_type == '3') {
            $lateusers = User::where('school_id', $school_id)
                ->where("position_id", 10)->where('is_actived', true)
                ->with('department:id,name,start_at')
                ->with(['records' => function ($recordQuery) use ($today) {
                    $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
                }])
                ->get();
        } else if ($student_type == '2') {
            $lateusers = User::where('school_id', $school_id)
                ->where("position_id", 10)->where('is_actived', true)
                ->with('department:id,name,start_at')
                ->with(['tempers' => function ($temperQuery) use ($today) {
                    $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
                }])
                ->get();
        }

        $lateusers = $lateusers->map(function ($lateuser) use ($table_str, $time_str) {
            $collection =  collect([
                'id' => $lateuser->id,
                'name' => $lateuser->profile->name,
                'start' => $lateuser->department == null ? null : $lateuser->department->start_at,
                'date_time' => $lateuser->$table_str->first()[$time_str],
            ]);
            return $collection;
            //遲到以外的為null
        })
            ->filter(function ($lateuser) {
                return $lateuser['start'] !== null;
            })
            ->filter(function ($lateuser) {
                return $lateuser['date_time'] !== null;
            })
            ->filter(function ($lateuser) {
                return Carbon::parse($lateuser['date_time'])->gt(Carbon::parse($lateuser['start']));
            });
        $lateusers_count = $lateusers->count();
        return response()->json(['data' => $lateusers, 'count' => $lateusers_count]);
    }
    public function getabsentStudent(Request $request)
    {
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        // $today = '2020-10-19';
        // $late = Carbon::parse('10:00');
        $school_id = $request->school_id;
        $student_type = $this->student_school_type($school_id);
        $table_str = $this->record_tempers($student_type);
        $time_str = $this->date_record($student_type);

        if ($student_type == '1' || $student_type == '3') {
            $students = User::where('school_id', $school_id)
                ->where("position_id", 10)->where('is_actived', true)
                ->with(['records' => function ($recordQuery) use ($today) {
                    $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
                }])
                ->get();
        } else if ($student_type == '2') {
            $students = User::where('school_id', $school_id)
                ->where("position_id", 10)->where('is_actived', true)
                ->with(['tempers' => function ($temperQuery) use ($today) {
                    $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
                }])
                ->get();
        }

        $students = $students->map(function ($student) use ($table_str, $time_str) {
            $collection =  collect([
                'id' => $student->id,
                'name' => $student->profile->name,
                'date_time' => $student->$table_str->first()[$time_str],
            ]);
            return $collection;
        })->filter(function ($student) {
            return $student['date_time'] == null;
        })->count();

        return $students;
    }
    public function getabsentTeacher(Request $request)
    {
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $school_id = $request->school_id;
        $student_type = $this->student_school_type($school_id);
        $table_str = $this->record_tempers($student_type);
        $time_str = $this->date_record($student_type);

        if ($student_type == '1' || $student_type == '3') {
            $teachers = User::where('school_id', $school_id)
                ->whereNotIn('position_id', [10, 20])->where('is_actived', true)
                ->with(['records' => function ($recordQuery) use ($today) {
                    $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
                }])
                ->get();
        } else if ($student_type == '2') {
            $teachers = User::where('school_id', $school_id)
                ->whereNotIn('position_id', [10, 20])->where('is_actived', true)
                ->with(['tempers' => function ($temperQuery) use ($today) {
                    $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
                }])
                ->get();
        }

        $teachers = $teachers->map(function ($teacher) use ($table_str, $time_str) {
            $collection =  collect([
                'id' => $teacher->id,
                'name' => $teacher->profile->name,
                'date_time' => $teacher->$table_str->first()[$time_str],
            ]);
            return $collection;
        })->filter(function ($teacher) {
            return $teacher['date_time'] == null;
        })->count();

        return $teachers;
    }

    public function getAttence(Request $request)
    {
        $a = array(
            "6" => 0, //"Sum"
            "0" => 0,  //"Mon"
            "1" => 0, //"Tue"
            "2" => 0, //"Wed"
            "3" => 0, //"Thu"
            "4" => 0, //"Fri"
            "5" => 0, //"Sat"
        );

        $departments = Department::where('departments.school_id', $request->school_id)
            ->join('users', 'users.id', '=', 'departments.supervisor_id')
            ->select('departments.*', 'users.name as owner_name', 'users.avatar')->get(['id']);

        if ($request->department_id == "") {
            $users = User::where('users.school_id', $request->school_id)
                ->where('is_actived', '=', 1)
                ->get(['id']);
        } else {
            $users = User::where('users.school_id', $request->school_id)
                ->where('is_actived', '=', 1)
                ->where('department_id', '=', $request->department_id)->get();
        }

        $total_users = $users->count();
        //$timezone = config('services.time_zone');
        //return $departments;
        for ($i = 0; $i < 7; $i++) {
            if ($i == 0) {
                $day = date('Y-m-d', strtotime($request->last_monday));
            } else {
                $day = date('Y-m-d', strtotime($request->last_monday . ' + ' . $i . ' days'));
            }

            $records = Record::where('date_time', 'like', '%' . $day . '%')->whereIn('user_id', $users->toArray())
                ->groupBy('user_id')
                ->get(['user_id']);
            // 分子
            $showUp = $records->count();
            // 分母
            $total = $users->count();
            $a[strval($i)] = $showUp / $total;
        }

        // ->join('users','users.id','=','departments.supervisor_id')
        // ->select('departments.*','users.name as owner_name','users.avatar')->get();
        // $today = Carbon::now($timezone)->format('Y-m-d');
        // $departments = $departments->map(function($department)use($today){
        //     $total = $department->users()->get();

        //     $showUp = $total->filter(function($user)use($today){
        //         $user = $user->records()->where('records.date_time','like','%'.$today.'%')->first();
        //         return $user;
        //     });

        //     $department->showUp = $showUp->count();
        //     $department->total = $total->count();
        //     return $department;
        // });
        // return $departments->chunk(5);
        return collect($a);
    }

    public function temperature_check(Request $request)
    {
        $admin_id = (int) $request->admin_id;
        $temperature_id = $request->temperature_id;
        // return $temperature_id;
        $temperature = Temperature::find($temperature_id);
        $temperature->update([
            'check' => true,
        ]);
        activity()
            ->causedBy(Admin::find($admin_id))
            ->performedOn($temperature)
            ->withProperties(['type' => 'edit'])
            ->log('溫度確認');

        $this->error_temperature_push($temperature);

        return $this->succeed('', 200);
    }
    public function error_temperature_push($temperature)
    {
        $user_id = $temperature->user_id;
        $temperature_val = $temperature->temperature_val;
        $name = $temperature->recognition_name;
        $teacher_id = User::find($user_id)->department->supervisor_id;
        $teacher = User::find($teacher_id);
        $parent_id = spu_relationship::where('user_id', $user_id)->first()->parent_id;
        $parent = Parents::find($parent_id);

        $data = [
            'id' => '溫度異常通知',
            'title' => $name . '溫度異常通知',
            'message' => $temperature_val,
            'type' => 'emergency',
            'token' => [$teacher->device_token, $parent->device_token],
            'sound' => 'spaceship_alarm.mp3',
        ];
        // return $data;
        $job = (new FCMNotification($data))->onConnection('redis_high');
        $this->dispatch($job);
    }
}
