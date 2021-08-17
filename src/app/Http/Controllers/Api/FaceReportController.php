<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\API\ApiHelper;
use App\Http\Resources\Api\GetList;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Record;
use App\Temperature;
use App\Parents;
use App\spu_relationship;
use App\User;
use App\Department;

class FaceReportController extends Controller
{
    use ApiHelper;

    public function analysis_day($record_list)
    {
        $timezone = config('services.time_zone');
        $time_arrive = null;
        $time_leaved = null;

        $time_duration = 0;

        if (!isset($record_list) || sizeof($record_list) > 0)
        {
            $time_arrive = $record_list->min('record_time');
            $time_leaved = $record_list->max('record_time');
        }

        if (isset($time_arrive) && isset($time_leaved))
        {
            $time_start_object = Carbon::parse($time_arrive, $timezone);
            $time_ended_object = Carbon::parse($time_leaved, $timezone);
            $time_duration = $time_ended_object->diffInSeconds($time_start_object);
        }

        $output = [
            'time_arrive' => $time_arrive,
            'time_leaved' => $time_leaved,
            'time_duration_seconds' => $time_duration,
        ];

        return $output;
    }
    public function analysis_temperatures($record_list)
    {
        $timezone = config('services.time_zone');
        $temperature_lowest = null;
        $temperature_highest = null;
        $record_time = null;

        if (!isset($record_list) || sizeof($record_list) > 0)
        {
            $temperature_lowest = (float) $record_list->min('temperature_val');
            $temperature_highest = (float) $record_list->max('temperature_val');
        }

        $output = [
            'temperature_lowest' => $temperature_lowest,
            'temperature_highest' => $temperature_highest,
        ];

        return $output;
    }
    public function day_work(Request $request)
    {
        $user_id = (int) $request->input('user_id');
        $day = $request->input('day');
        $timezone = config('services.time_zone');


        $search_start_date = Carbon::parse($day, $timezone)->firstOfMonth()->toDateString() ." 00:00:00";
        $search_ended_date = Carbon::parse($day, $timezone)->lastOfMonth()->toDateString() ." 23:59:59";

        // $search_start_date = Carbon::parse($day, $timezone)->firstOfMonth();
        // $search_ended_date = Carbon::parse($day, $timezone)->lastOfMonth();


        $record_list = Temperature::where('user_id', '=', $user_id)
            ->whereBetween('record_time', [$search_start_date, $search_ended_date])
            ->get();


        $today_data_exis = $record_list->whereBetween('record_time', [
            Carbon::parse($day, $timezone)->toDateString() ." 00:00:00",
            Carbon::parse($day, $timezone)->toDateString() ." 23:59:59",
        ]);

        $record_group = $record_list->groupBy(function($record) use ($timezone) {
            return Carbon::parse($record->record_time, $timezone)->format('Y-m-d');
        });

        $today_data = (count($today_data_exis) > 0) ? $record_group[$day] : [];
        $today_analysis = $this->analysis_day($today_data);
        $today_work_hour = round($today_analysis['time_duration_seconds'] / 3600, 2);

        $array_start_date = [];
        $array_ended_date = [];
        $array_duration_seconds = [];

        foreach ($record_group as $current_day=>$current_record_list) {
            $current_analysis = $this->analysis_day($current_record_list);
            $array_start_date[] = $current_analysis['time_arrive'];
            $array_ended_date[] = $current_analysis['time_leaved'];
            $array_duration_seconds[] = $current_analysis['time_duration_seconds'];
        }

        $array_start_date = array_values(array_filter($array_start_date));
        $array_ended_date = array_values(array_filter($array_ended_date));
        $array_duration_seconds = array_values(array_filter($array_duration_seconds));

        $month_avg_time_arrive = null;
        $month_avg_time_leaved = null;
        $month_avg_work_hour = 0;

        if (sizeof($array_start_date) > 0) {
            $array_secondes = collect($array_start_date)->map(function($date_string) use ($timezone) {
                return Carbon::parse($date_string, $timezone)->diffInSeconds(Carbon::parse($date_string, $timezone)->startOfDay());
            })->toArray();

            $month_avg_time_arrive = gmdate('H:i:s', array_sum($array_secondes) / count($array_secondes));
        }
        if (sizeof($array_ended_date) > 0) {
            $array_secondes = collect($array_ended_date)->map(function($date_string) use ($timezone) {
                return Carbon::parse($date_string, $timezone)->diffInSeconds(Carbon::parse($date_string, $timezone)->startOfDay());
            })->toArray();

            $month_avg_time_leaved = gmdate('H:i:s', array_sum($array_secondes) / count($array_secondes));
        }
        if (sizeof($array_duration_seconds) > 0) {
            $month_avg_work_hour = round(array_sum($array_duration_seconds) / count($array_duration_seconds) / 3600, 2);
        }

        $output = [
            'User_id' => $user_id,
            'Date' => $day,
            'tv_arrival_time' => $today_analysis['time_arrive'],
            'tv_leave_time' => $today_analysis['time_leaved'],
            'tv_work_hour' => $today_work_hour,
            'tv_analysis_arrival_time' => $month_avg_time_arrive,
            'tv_analysis_leave_time' => $month_avg_time_leaved,
            'tv_analysis_working_hours_time' => $month_avg_work_hour,
        ];

        return response()->json($output);
    }
    public function week_work(Request $request)
    {
        $user_id = (int) $request->input('user_id');
        $sday = $request->input('sday');
        $eday = $request->input('eday');
        $timezone = config('services.time_zone');

        $weekday_names = ['Sun', 'Mon', 'Tue', 'Wed', 'Thr', 'Fri', 'Sat'];
        $workday_list_hours = [
            'Sun' => null,
            'Mon' => null,
            'Tue' => null,
            'Wed' => null,
            'Thr' => null,
            'Fri' => null,
            'Sat' => null,
        ];
        $workday_list_times = [
            'Sun' => null,
            'Mon' => null,
            'Tue' => null,
            'Wed' => null,
            'Thr' => null,
            'Fri' => null,
            'Sat' => null,
        ];

        $search_start_date = Carbon::parse($sday, $timezone)->startOfDay();
        $search_ended_date = Carbon::parse($eday, $timezone)->endOfDay();

        $record_list = Temperature::where('user_id', '=', $user_id)
            ->whereBetween('record_time', [$search_start_date, $search_ended_date])
            ->get();

        $record_group = $record_list->groupBy(function($record) use ($timezone) {
            return Carbon::parse($record->record_time, $timezone)->format('Y-m-d');
        });

        $array_start_date = [];
        $array_ended_date = [];
        $array_duration_seconds = [];

        foreach ($record_group as $current_day=>$current_record_list) {
            $current_analysis = $this->analysis_day($current_record_list);
            $array_start_date[] = $current_analysis['time_arrive'];
            $array_ended_date[] = $current_analysis['time_leaved'];
            $array_duration_seconds[] = $current_analysis['time_duration_seconds'];
            $current_date = Carbon::parse($current_day, $timezone);
            $week_day_name = $weekday_names[$current_date->weekday()];
            $workday_list_hours[$week_day_name] = round($current_analysis['time_duration_seconds'] / 3600, 2);
            $workday_list_times[$week_day_name] = $current_analysis['time_arrive'];
        }

        $array_start_date = array_values(array_filter($array_start_date));
        $array_ended_date = array_values(array_filter($array_ended_date));
        $array_duration_seconds = array_values(array_filter($array_duration_seconds));

        $week_avg_time_arrive = null;
        $week_avg_time_leaved = null;
        $week_sum_work_hour = 0;
        $week_avg_work_hour = 0;
        $week_count_late = 0;

        if (sizeof($array_start_date) > 0) {
            $array_secondes = collect($array_start_date)->map(function($date_string) use ($timezone) {
                return Carbon::parse($date_string, $timezone)->diffInSeconds(Carbon::parse($date_string, $timezone)->startOfDay());
            })->toArray();

            $week_avg_time_arrive = gmdate('H:i:s', array_sum($array_secondes) / count($array_secondes));
        }
        if (sizeof($array_ended_date) > 0) {
            $array_secondes = collect($array_ended_date)->map(function($date_string) use ($timezone) {
                return Carbon::parse($date_string, $timezone)->diffInSeconds(Carbon::parse($date_string, $timezone)->startOfDay());
            })->toArray();

            $week_avg_time_leaved = gmdate('H:i:s', array_sum($array_secondes) / count($array_secondes));
        }
        if (sizeof($array_duration_seconds) > 0) {
            $week_sum_work_hour = round(array_sum($array_duration_seconds) / 3600, 2);
            $week_avg_work_hour = round(array_sum($array_duration_seconds) / count($array_duration_seconds) / 3600, 2);
        }

        foreach ($array_start_date as $current_day) {
            $current_date = Carbon::parse($current_day, $timezone);
            if ($current_date > Carbon::parse($current_day, $timezone)->startOfDay()->addHours(9)) {
                $week_count_late += 1;
            }
        }

        $output = [
            'user_id' => $user_id,
            'sDate' => $sday,
            'eDate' => $eday,
            'tv_work_hors' => $week_sum_work_hour,
            'tv_work_late' => $week_count_late,
            'tv_work_hour' => $week_avg_work_hour,
            'tv_analysis_time' => $week_avg_time_arrive,
            'tv_analysis_leave_time' => $week_avg_time_leaved,
            'tv_analysis_working_hours' => $week_avg_work_hour,
            'work_time' => $workday_list_times,
            'work_hours' => $workday_list_hours,
        ];

        return response()->json($output);
    }


    public function month_work(Request $request)
    {
        $user_id = (int) $request->input('user_id');
        $sday = $request->input('sday');
        $eday = $request->input('eday');
        $timezone = config('services.time_zone');

        $search_start_date = Carbon::parse($sday, $timezone)->startOfDay();
        $search_ended_date = Carbon::parse($eday, $timezone)->endOfDay();

        $record_list = Temperature::where('user_id', '=', $user_id)
            ->whereBetween('record_time', [$search_start_date, $search_ended_date])
            ->get();

        $record_group = $record_list->groupBy(function($record) use ($timezone) {
            return Carbon::parse($record->record_time, $timezone)->format('Y-m-d');
        });

        $array_start_date = [];
        $array_ended_date = [];
        $array_duration_seconds = [];
        $workday_list = [];

        foreach ($record_group as $current_day=>$current_record_list) {
            $current_analysis = $this->analysis_day($current_record_list);
            $array_start_date[] = $current_analysis['time_arrive'];
            $array_ended_date[] = $current_analysis['time_leaved'];
            $array_duration_seconds[] = $current_analysis['time_duration_seconds'];
        }

        foreach ($record_group as $current_day=>$current_record_list) {
            $current_analysis = $this->analysis_day($current_record_list);
            $workday_list[] = [
                'Date' => $current_day,
                'Arrival_time' => $current_analysis['time_arrive'],
                'Leave_time' => $current_analysis['time_leaved'],
                'Working_Hrs' => round($current_analysis['time_duration_seconds'] / 3600, 2),
            ];
        }

        $array_start_date = array_values(array_filter($array_start_date));
        $array_ended_date = array_values(array_filter($array_ended_date));
        $array_duration_seconds = array_values(array_filter($array_duration_seconds));

        $month_avg_time_arrive = null;
        $month_avg_time_leaved = null;
        $month_sum_work_hour = 0;
        $month_avg_work_hour = 0;
        $month_count_late = 0;

        if (sizeof($array_start_date) > 0) {
            $array_secondes = collect($array_start_date)->map(function($date_string) use ($timezone) {
                return Carbon::parse($date_string, $timezone)->diffInSeconds(Carbon::parse($date_string, $timezone)->startOfDay());
            })->toArray();

            $month_avg_time_arrive = gmdate('H:i:s', array_sum($array_secondes) / count($array_secondes));
        }
        if (sizeof($array_ended_date) > 0) {
            $array_secondes = collect($array_ended_date)->map(function($date_string) use ($timezone) {
                return Carbon::parse($date_string, $timezone)->diffInSeconds(Carbon::parse($date_string, $timezone)->startOfDay());
            })->toArray();

            $month_avg_time_leaved = gmdate('H:i:s', array_sum($array_secondes) / count($array_secondes));
        }
        if (sizeof($array_duration_seconds) > 0) {
            $month_sum_work_hour = round(array_sum($array_duration_seconds) / 3600, 2);
            $month_avg_work_hour = round(array_sum($array_duration_seconds) / count($array_duration_seconds) / 3600, 2);
        }

        foreach ($array_start_date as $current_day) {
            $current_date = Carbon::parse($current_day, $timezone);
            if ($current_date > Carbon::parse($current_day, $timezone)->startOfDay()->addHours(9)) {
                $month_count_late += 1;
            }
        }

        $output = [
            'User_id' => $user_id,
            'sDate' => $sday,
            'eDate' => $eday,
            'tv_work_hors' => $month_sum_work_hour,
            'tv_work_late' => $month_count_late,
            'tv_leave_time' => $month_avg_work_hour,
            'workList' => $workday_list,
        ];

        return response()->json($output);
    }


    public function today_temperatures(Request $request)
    {
        $user_id = (int) $request->input('user_id');
        $day = $request->input('day');
        $timezone = config('services.time_zone');

        $search_start_date = Carbon::parse($day, $timezone)->startOfDay();
        $search_ended_date = Carbon::parse($day, $timezone)->endOfDay();

        $record_list = Temperature::where('user_id', '=', $user_id)
            ->whereBetween('record_time', [$search_start_date, $search_ended_date])
            ->get();

        $record_group = $record_list->groupBy(function($record) use ($timezone) {
            return Carbon::parse($record->record_time, $timezone)->format('Y-m-d');
        });

        $array_temperature_highest = [];
        $array_temperature_lowest = [];
        $temperature_val = null;
        $record_time = null;
        $recognition_name = null;
        $created_at = null;

        foreach ($record_list as $current_record) {
        	if ($temperature_val == null || $temperature_val < (float) $current_record->temperature_val) {
                $temperature_val = (float) $current_record->temperature_val;
                $recognition_name = $current_record->recognition_name;
                $record_time = Carbon::parse($current_record->record_time, $timezone)->format('Y-m-d H:i:s');
                $created_at = Carbon::parse($current_record->created_at, $timezone)->format('Y-m-d H:i:s');
        	}

        }

        $output = [
            'user_id' => $user_id,
            'recognition_name' => $recognition_name,
            'temperature_val' => $temperature_val,
            'record_time' => $record_time,
            'created_at' => $created_at,
        ];

        return response()->json($output);
    }


    public function week_temperatures(Request $request)
    {
        $user_id = (int) $request->input('user_id');
        $sday = $request->input('sday');
        $eday = $request->input('eday');
        $timezone = config('services.time_zone');

        $weekday_names = ['Sun', 'Mon', 'Tue', 'Wed', 'Thr', 'Fri', 'Sat'];
        $week_temperatures = [
            'Sun' => null,
            'Mon' => null,
            'Tue' => null,
            'Wed' => null,
            'Thr' => null,
            'Fri' => null,
            'Sat' => null,
        ];

        $search_start_date = Carbon::parse($sday, $timezone)->startOfDay();
        $search_ended_date = Carbon::parse($eday, $timezone)->endOfDay();

        $record_list = Temperature::where('user_id', '=', $user_id)
            ->whereBetween('record_time', [$search_start_date, $search_ended_date])
            ->get();

        $record_group = $record_list->groupBy(function($record) use ($timezone) {
            return Carbon::parse($record->record_time, $timezone)->format('Y-m-d');
        });

        $array_temperature_highest = [];
        $array_temperature_lowest = [];

        foreach ($record_group as $current_day=>$current_record_list) {
            $current_analysis = $this->analysis_temperatures($current_record_list);
            $array_temperature_lowest[] = $current_analysis['temperature_lowest'];
            $array_temperature_highest[] = $current_analysis['temperature_highest'];
        }
        foreach ($record_group as $current_day=>$current_record_list) {
            $current_analysis = $this->analysis_temperatures($current_record_list);
            $current_date = Carbon::parse($current_day, $timezone);
            $week_day_name = $weekday_names[$current_date->weekday()];
            $week_temperatures[$week_day_name] = $current_analysis['temperature_highest'];
        }

        $array_temperature_highest = array_values(array_filter($array_temperature_highest));
        $array_temperature_lowest = array_values(array_filter($array_temperature_lowest));

        $week_avg_temperatures = null;

        if (sizeof($array_temperature_highest) > 0) {
            $week_avg_temperatures = round(array_sum($array_temperature_highest) / count($array_temperature_highest), 2);
        }

        $output = [
            'user_id' => $user_id,
            'sDate' => $sday,
            'eDate' => $eday,
            'tv_avg_temperature' => $week_avg_temperatures,
            'week_temperatures' => $week_temperatures,
        ];

        return response()->json($output);
    }


    public function month_temperatures(Request $request)
    {
        $user_id = (int) $request->input('user_id');
        $sday = $request->input('sday');
        $eday = $request->input('eday');
        $timezone = config('services.time_zone');

        $month_temperatures = [];

        $search_start_date = Carbon::parse($sday, $timezone)->startOfDay();
        $search_ended_date = Carbon::parse($eday, $timezone)->endOfDay();

        $record_list = Temperature::where('user_id', '=', $user_id)
            ->whereBetween('record_time', [$search_start_date, $search_ended_date])
            ->get();

        $record_group = $record_list->groupBy(function($record) use ($timezone) {
            return Carbon::parse($record->record_time, $timezone)->format('Y-m-d');
        });

        $array_temperature_highest = [];
        $array_temperature_lowest = [];

        $period = CarbonPeriod::between($sday, $eday);
        foreach ($period as $date) {
            $month_temperatures['d'.$date->day] = null;
        }

        foreach ($record_group as $current_day=>$current_record_list) {
            $current_analysis = $this->analysis_temperatures($current_record_list);
            $array_temperature_lowest[] = $current_analysis['temperature_lowest'];
            $array_temperature_highest[] = $current_analysis['temperature_highest'];
        }
        foreach ($record_group as $current_day=>$current_record_list) {
            $current_analysis = $this->analysis_temperatures($current_record_list);
            $current_date = Carbon::parse($current_day, $timezone);
            $month_temperatures['d'.$current_date->day] = $current_analysis['temperature_highest'];
        }

        $array_temperature_highest = array_values(array_filter($array_temperature_highest));
        $array_temperature_lowest = array_values(array_filter($array_temperature_lowest));

        $week_avg_temperatures = null;

        if (sizeof($array_temperature_highest) > 0) {
            $week_avg_temperatures = round(array_sum($array_temperature_highest) / count($array_temperature_highest), 2);
        }

        $output = [
            'user_id' => $user_id,
            'sDate' => $sday,
            'eDate' => $eday,
            'tv_avg_temperature' => $week_avg_temperatures,
            'month_temperatures' => $month_temperatures,
        ];

        return response()->json($output);
    }


    public function parents_relations(Request $request)
    {
        $parents_phone = $request->input('phone');

        $parents_id = '';
        $parents_name = '';
        $parents_ename = '';
        $parents_relation = '';
        $students_list = [];

        $parents_info = Parents::where('phone', '=', $parents_phone)->first();

        if (isset($parents_info)) {
            $parents_name = $parents_info->name;
            $parents_ename = $parents_info->ename;
            $parents_relation = $parents_info->title;
            $parents_id = $parents_info->parent_id;
            $students_list = $parents_info->spu_relationship()->with('user.profile')->get()->pluck('user');
        }

        $output = [
            'parentsid' => $parents_id,
            'phone' => $parents_phone,
            'name' => $parents_name,
            'ename' => $parents_ename,
            'relationship' => $parents_relation,
            'students' => $students_list,
        ];

        return response()->json($output);
    }
    public function parents_relations2(Request $request)
    {
        $parents_phone = $request->input('phone');

        $parents_id = '';
        $parents_name = '';
        $parents_ename = '';
        $parents_relation = '';
        $device_token = '';
        $school_id = '';
        $students_list = [];

        $parents_info = Parents::where('phone', '=', $parents_phone)->first();

        if (isset($parents_info)) {
            $parents_name = $parents_info->name;
            $parents_ename = $parents_info->ename;
            $parents_relation = $parents_info->title;
            $parents_id = $parents_info->parent_id;
            $school_id = $parents_info->school_id;
            $device_token = $parents_info->device_token;

            $students_list = spu_relationship::where('parent_id', '=', $parents_id)
            ->with(['user' => function ($userQuery) {
                $userQuery->with('department.teacher')->with('profile')->with('user_type');
            }])
            ->get();

            $students_list = $students_list->map(function ($item){
                $collection =  collect ( [
                    'id' => $item->id,
                    'name' => $item->user->profile->name,
                    'mac' => $item->user->user_type->mac->mac,
                    'nick_name' => $item->user->profile->nickname,
                    'gender' => $item->user->profile->gender,
                    'birthday' => $item->user->profile->birthday,
                    'onboard_date' => $item->user->onboard_date,
                    'classname' => $item->user->department == null ? null : $item->user->department->name,
                    'supervisor_id' => $item->user->department == null ? null : $item->user->department->teacher->id,
                    'teachername' => $item->user->department == null ? null : $item->user->department->teacher->name,
                    'start_at' => $item->user->department == null ? null : $item->user->department->start_at,
                    'finish_at' => $item->user->department == null ? null : $item->user->department->finish_at,
                ]);
                return $collection;
            });

        }

        $output = [
            'parentsid' => $parents_id,
            'phone' => $parents_phone,
            'name' => $parents_name,
            'ename' => $parents_ename,
            'relationship' => $parents_relation,
            'school_id' => $school_id,
            'device_token' => $device_token,
            'students' => $students_list,
        ];


        return response()->json($output);
    }

    public function teacher_relations(Request $request)
    {
        $parents_phone = $request->input('phone');

        $parents_id = '';
        $teacher_id = '';
        $parents_name = '';
        $parents_ename = '';
        $parents_relation = '';
        $device_token = '';
        $school_id = '';
        $students_list = [];

        $teacher_info = User::where('phone', '=', $parents_phone)->first();
        // return $teacher_info;
        if (isset($teacher_info)) {
            $teacher_name = $teacher_info->profile->name; // 老師姓名
            $teacher_ename = $teacher_info->profile->nickname; // 英文姓名
            $teacher_relation = "Teacher";
            $teacher_id = $teacher_info->id; // userid
            $department_id = $teacher_info->departments()->select('id')->get(); // userid
            $school_id = $teacher_info->school_id;
            $device_token = $teacher_info->device_token;
            $students_list = User::whereIn('department_id',$department_id)
            ->with('profile')->with('user_type')->with('spu_relationship.parent')->with('department')
            ->get();
            $students_list = $students_list->map(function ($item){
                $collection =  collect ( [
                    'id' => $item->id,
                    'name' => $item->profile->name,
                    'uuid' => $item->user_type->uuid == null ? null : $item->user_type->uuid->uuid,
                    'nick_name' => $item->profile->nickname,
                    'gender' => $item->profile->gender,
                    'birthday' => $item->profile->birthday,
                    'onboard_date' => $item->onboard_date,
                    'classname' => $item->department->name,
                    'supervisor_id' => $item->department->supervisor_id,
                    'start_at' => $item->department->start_at,
                    'finish_at' => $item->department->finish_at,
                    'parent_id' => $item->spu_relationship == null ? null : $item->spu_relationship->parent->id,
                    'parent_name' => $item->spu_relationship == null ? null : $item->spu_relationship->parent->name,
                ]);
                return $collection;
            });
        }

        $output = [
            'parentsid' => '',
            'teacherid' => $teacher_id,
            'phone' => $parents_phone,
            'name' => $parents_name,
            'ename' => $parents_ename,
            'relationship' => $parents_relation,
            'school_id' => $school_id,
            'device_token' => $device_token,
            'students' => $students_list,
        ];


        return response()->json($output);
    }

}
