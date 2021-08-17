<?php

namespace App\Http\Controllers;

use App\API\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Department;
use App\User;
use App\Admin;
use App\School;
use App\Record;
use App\Temperature;
use App\Attendance;
use App\Leave;
use Illuminate\Support\Facades\Validator;
use DB;

class AttendanceController extends Controller
{
    use ApiHelper;

    public function date_record($type)
    {
        if ($type == '1' || $type == '3') {
            return 'date_time';
        } else if ($type == '2') {
            return 'record_time';
        }
    }
    public function temper_record($type)
    {
        if ($type == '1' || $type == '3') {
            return 'records';
        } else if ($type == '2') {
            return 'tempers';
        }
    }
    public function group($record_list, $temper_list, $type, $time_str, $timezone, $leave_list = null)
    {
        $leave_group = null;
        if ($leave_list) {
            $leave_group = $leave_list->groupBy(function ($item) use ($timezone) {
                return $item->user_id . '_' . Carbon::parse($item->updated_at, $timezone)->format('Y-m-d');
            })->map(function ($item, $key) use ($timezone) {
                $time_leaved = null;
                if (!isset($item) || sizeof($item) > 0) {
                    $time_leaved = $item->max('updated_at');
                }
                $current_analysis = [
                    'time_leaved' => $time_leaved,
                ];
                $leaved = Carbon::parse($current_analysis['time_leaved'], $timezone)->format('H:i');
                $collection =  collect([
                    'user_id' => $item->last()['user_id'],
                    'name' => $item->last()['students']['profile']['name'],
                    'qrcode_time_leaved' => $leaved,
                ]);
                return $collection;
            });
        }
        $record_group = $record_list->groupBy(function ($item) use ($timezone, $time_str) {
            return $item->user_id . '_' . Carbon::parse($item->$time_str, $timezone)->format('Y-m-d');
        })->map(function ($item, $key) use ($type, $timezone) {
            $current_analysis = $this->analysis_day($item, $type);
            $arrive = Carbon::parse($current_analysis['time_arrive'], $timezone)->format('H:i');
            $leaved = Carbon::parse($current_analysis['time_leaved'], $timezone)->format('H:i');
            $collection =  collect([
                'user_id' => $item->last()['user_id'],
                'name' => $item->last()['user']['profile']['name'],
                'time_arrive' => $arrive,
                'time_leaved' => $leaved == $arrive ? null : $leaved,
            ]);
            return $collection;
        });
        $temper_group = $temper_list->groupBy(function ($item) use ($timezone) {
            return $item->user_id . '_' . Carbon::parse($item->record_time, $timezone)->format('Y-m-d');
        })->map(function ($item, $key) use ($type) {
            $collection =  collect([
                'user_id' => $item->last()['user_id'],
                'name' => $item->last()['user']['profile']['name'],
                'temperature_val' => $item->last()['temperature_val'],
            ]);
            return $collection;
        });
        $user = array_merge_recursive($record_group->toArray(), $temper_group->toArray(), $leave_group ? $leave_group->toArray() : []);
        // return $user;
        $user_list = [];
        if ($leave_group) {
            foreach ($user as $key => $item) {
                $key = [
                    'user_id' => gettype($item['user_id']) == "array" ? $item['user_id'][0] : $item['user_id'],
                    'name' => gettype($item['name']) == "array" ? $item['name'][0] : $item['name'],
                    'time_arrive' => array_key_exists('time_arrive', $item) ? $item['time_arrive'] : null,
                    // 未到校不顯示離校時間
                    'time_leaved' => array_key_exists('time_arrive', $item) ? (array_key_exists('qrcode_time_leaved', $item) ? $item['qrcode_time_leaved'] : null) : null,
                    'temperature_val' => array_key_exists('temperature_val', $item) ? $item['temperature_val'] : null,
                    'date' => substr(strrchr($key, '_'), 1),
                ];
                array_push($user_list, $key);
            }
        } else {
            foreach ($user as $key => $item) {
                $key = [
                    'user_id' => gettype($item['user_id']) == "array" ? $item['user_id'][0] : $item['user_id'],
                    'name' => gettype($item['name']) == "array" ? $item['name'][0] : $item['name'],
                    'time_arrive' => array_key_exists('time_arrive', $item) ? $item['time_arrive'] : null,
                    'time_leaved' => array_key_exists('time_leaved', $item) ? $item['time_leaved'] : null,
                    'temperature_val' => array_key_exists('temperature_val', $item) ? $item['temperature_val'] : null,
                    'date' => substr(strrchr($key, '_'), 1),
                ];
                array_push($user_list, $key);
            }
        }

        return $user_list;
    }
    public function analysis_day($record_list, $type)
    {
        $time_arrive = null;
        $time_leaved = null;
        if (!isset($record_list) || sizeof($record_list) > 0) {
            if ($type == '1' || $type == '3') {
                $time_arrive = $record_list->min('date_time');
                $time_leaved = $record_list->max('leave_at');
            } else if ($type == '2') {
                $time_arrive = $record_list->min('record_time');
                $time_leaved = $record_list->max('record_time');
            }
        }
        $output = [
            'time_arrive' => $time_arrive,
            'time_leaved' => $time_leaved,
        ];

        return $output;
    }
    // public function temper_day($temper_list)
    // {
    //     $temperature_val = null;
    //     if (!isset($temper_list) || sizeof($temper_list) > 0)
    //     {
    //         $temperature_val = $temper_list->last()['temperature_val'];
    //     }
    //     $output = [
    //         'temperature_val' => $temperature_val,
    //     ];

    //     return $output;
    // }
    // public function record_list_check($user,$student_type, $search_start_date, $search_ended_date)
    // {
    //     if ($student_type == '1' || $student_type == '3') {
    //         $record_list = $user
    //         ->with(['records' => function ($recordQuery) use($search_start_date, $search_ended_date) {
    //             $recordQuery->whereBetween('records.date_time', [$search_start_date, $search_ended_date]);
    //         }])
    //         ->with(['tempers' => function ($temperQuery) use($search_start_date, $search_ended_date) {
    //             $temperQuery->whereBetween('temperatures.record_time', [$search_start_date, $search_ended_date]);
    //         }])
    //         ->get();
    //     } else if ($student_type == '2') {
    //         $record_list = Temperature::whereBetween($time_str, [$search_start_date, $search_ended_date])
    //         ->get();
    //     }
    //     return $record_list;
    // }
    public function leave_list_check($user, $search_start_date, $search_ended_date)
    {
        $leave_list = Leave::whereIn('user_id', $user)
            ->where('status', 1)
            ->whereBetween('updated_at', [$search_start_date, $search_ended_date])
            ->with('students.profile')
            ->get();
        return $leave_list;
    }
    public function record_list_check($user, $type, $time_str, $search_start_date, $search_ended_date)
    {
        if ($type == '1' || $type == '3') {
            $record_list = Record::whereIn('user_id', $user)
                ->whereBetween($time_str, [$search_start_date, $search_ended_date])
                ->with('user.profile')
                ->get();
        } else if ($type == '2') {
            $record_list = Temperature::whereIn('user_id', $user)
                ->whereBetween($time_str, [$search_start_date, $search_ended_date])
                ->with('user.profile')
                ->get();
        }
        return $record_list;
    }
    public function temper_list_check($user, $search_start_date, $search_ended_date)
    {

        $temper_list = Temperature::whereIn('user_id', $user)
            ->whereBetween('record_time', [$search_start_date, $search_ended_date])
            ->with('user.profile')
            ->get();

        return $temper_list;
    }
    public function departmentsName(Request $request)
    {
        $school_id = $request->school_id;
        $departments = Department::where('school_id', $school_id)->get();
        $departments = $departments->map(function ($department) {
            $collection =  collect([
                'id' => $department->id,
                'name' => $department->name,
            ]);
            return $collection;
        });
        return $departments;
    }
    public function getAttendance_student(Request $request)
    {
        $school_id = $request->school_id;
        $department_id = $request->department_id;
        $start = $request->start; //string
        $end = $request->end; //string
        $timezone = config('services.time_zone');
        $start = Carbon::parse($start, $timezone)->startOfDay(); //00:00:00
        $end = Carbon::parse($end, $timezone)->endOfDay(); //23:59:59
        $student_type = School::find($school_id)->student_type;
        $tabel_str = $this->temper_record($student_type);
        $time_str = $this->date_record($student_type);

        $user = User::where('department_id', $department_id)
            ->where('position_id', 10)->where('is_actived', true)->where('school_id', $school_id)
            ->select('id')->get()->pluck('id');
        //取得時間範圍內的user所有時間溫度紀錄
        $record_list = $this->record_list_check($user, $student_type, $time_str, $start, $end);
        $temper_list = $this->temper_list_check($user, $start, $end);
        $leave_list = $this->leave_list_check($user, $start, $end);

        //group
        $student_list = $this->group($record_list, $temper_list, $student_type, $time_str, $timezone, $leave_list);
        return $student_list;
        //依時間做區分;
        // $record_group = $record_list->map(function($item) use ($timezone, $time_str) {
        //     $records = $item->records->groupBy(function($record_item) use ($timezone, $time_str){
        //         return Carbon::parse($record_item->$time_str, $timezone)->format('Y-m-d');
        //     });
        //     $tempers = $item->tempers->groupBy(function($temper_item) use ($timezone){
        //         return Carbon::parse($temper_item->record_time, $timezone)->format('Y-m-d');
        //     });
        //     $collection =  collect ( [
        //         'id' => $item->id,
        //         'name' => $item->profile->name,
        //         'tempers' => $tempers,
        //         'records' => $records,
        //     ]);

        //     return $collection;
        // });
        // return $record_group;
        // //取出每個時段的最早最晚時間及溫度;
        // $students = $record_group->map(function ($item, $key) use ($student_type) {

        //     $records = $item['records']->map(function($record_item) use ($item, $student_type){
        //         $current_analysis = $this->analysis_day($record_item, $student_type);
        //         $collection =  collect ( [
        //             'id' => $item['id'],
        //             'name' => $item['name'],
        //             'day_analysis' => $current_analysis,
        //         ]);
        //         return $collection;
        //     });
        //     $tempers = $item['tempers']->map(function($temper_item) use ($item, $student_type){
        //         $collection =  collect ( [
        //             'id' => $item['id'],
        //             'name' => $item['name'],
        //             'temperature_val' => $temper_item->last()['temperature_val'],
        //         ]);
        //         return $collection;
        //     });

        //     $collection =  collect ( [
        //         'time' =>$records->keys()->merge($tempers->keys()),
        //         'records' => $records,
        //         'tempers' => $tempers,
        //     ]);

        //     return $collection;
        // });
        // $students = $students['time']->map(function ($item, $key) {
        //     pluck('name', 'product_id')
        // });
        // return $students;
    }
    public function getAttendance_teacher(Request $request)
    {
        $school_id = $request->school_id;
        $start = $request->start; //string
        $end = $request->end; //string
        $timezone = config('services.time_zone');
        $start = Carbon::parse($start, $timezone)->startOfDay(); //00:00:00
        $end = Carbon::parse($end, $timezone)->endOfDay(); //23:59:59
        $teacher_type = School::find($school_id)->teacher_type;
        $tabel_str = $this->temper_record($teacher_type);
        $time_str = $this->date_record($teacher_type);

        $user = User::whereNotIn('position_id', [10, 20])->where('is_actived', true)->where('school_id', $school_id)
            ->select('id')->get()->pluck('id');
        //取得時間範圍內的user所有時間溫度紀錄
        $record_list = $this->record_list_check($user, $teacher_type, $time_str, $start, $end);
        $temper_list = $this->temper_list_check($user, $start, $end);
        //group
        $teacher_list = $this->group($record_list, $temper_list, $teacher_type, $time_str, $timezone);
        return $teacher_list;
    }
    public function index(Request $request)
    {
        $department_id = $request->department_id;
        $date = $request->date;
        $timezone = config('services.time_zone');
        $date = Carbon::parse($date)->setTimezone($timezone)->toDateString();
        //驗證
        $validator = Validator::make(
            [
                'department_id' => $department_id,
                'date' => $date,
            ],
            [
                'department_id' => 'required',
                'date' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('要求日期、班級', $validator->errors(), 401);
        }
        $students = User::where('department_id', $department_id)
            ->where("position_id", 10)->where("is_actived", true)->select('id')->pluck('id');
        $attendance = Attendance::whereIn('user_id', $students)
            ->where('date', $date)
            ->with('user.profile')
            ->get();
        $exist_student = $attendance->pluck('user_id');
        $not_exist = $students->diff($exist_student);
        if (!$not_exist->isEmpty()) {
            $attendance = $this->foreachstore($not_exist, $date, $students);
        }
        $attendance = $attendance->map(function ($item) {
            return [
                'attendance_id' => $item->id,
                'name' => $item->user->profile->name,
                'leave' => $item->leave,
            ];
        });
        return $this->succeed($attendance, 200);
    }
    public function edit(Request $request)
    {
        $attendance_id = $request->attendance_id;
        $admin_id = $request->admin_id;
        $leave = $request->leave; //0:未到, 1:有到, 2:病假, 3:事假
        Attendance::where('id', $attendance_id)->update([
            "leave" => $leave,
        ]);

        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn(Attendance::where('id', $attendance_id)->first())
                ->withProperties([
                    'type' => 'edit',
                    'result' => 'success',
                ])
                ->log('編輯出勤表');
        }
        return $this->succeed('', 200);
    }
    public function foreachstore($not_exist, $date, $students)
    {
        foreach ($not_exist as $value) {
            //get student temper & parent
            $student = User::where('id', $value)
                ->where('position_id', 10)
                ->where('is_actived', 1)
                ->with('profile:user_id,name')
                ->with(['tempers' => function ($temperQuery) use ($date) {
                    $temperQuery->where('temperatures.record_time', 'like', '%' . $date . '%')->orderBy('id', 'desc');
                }])
                ->first();
            //新增一筆空資料
            //new contact
            $attendance = new Attendance([
                'user_id' => $student->id,
                'leave' => $student->tempers->first()['temperature_val'] ? 1 : 0,
                'date' => $date
            ]);
            $attendance->save();
        }
        //重抓資料
        $attendance = Attendance::whereIn('user_id', $students)
            ->where('date', $date)
            ->with('user.profile')
            ->get();
        return $attendance;
    }
}
