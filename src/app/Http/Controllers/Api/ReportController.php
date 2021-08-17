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
use App\Leave;
use App\spu_relationship;
use App\User;
use App\Department;

class ReportController extends Controller
{
    use ApiHelper;
    public function student_school_type($user_id)
    {
        $student_type = User::find($user_id)->school->student_type;

        return $student_type;
    }
    public function date_record($student_type)
    {
        if ($student_type == '1' || $student_type == '3') {
            return 'date_time';
        } else if ($student_type == '2') {
            return 'record_time';
        }
    }
    public function record_list_check($student_type, $user_id, $time_str, $search_start_date, $search_ended_date)
    {

        if ($student_type == '1' || $student_type == '3') {
            $record_list = Record::where('user_id', '=', $user_id)
                ->whereBetween($time_str, [$search_start_date, $search_ended_date])
                ->get();
        } else if ($student_type == '2') {
            $record_list = Temperature::where('user_id', '=', $user_id)
                ->whereBetween($time_str, [$search_start_date, $search_ended_date])
                ->get();
            // $record_list = Temperature::where('temperatures.user_id', $user_id)
            //     ->whereBetween($time_str, [$search_start_date, $search_ended_date])
            //     ->join('leaves', function ($join) use ($time_str) {
            //         $join->on('temperatures.user_id', 'leaves.user_id');
            //     })
            //     ->get();
        }
        return $record_list;
    }
    public function analysis_day($record_list, $student_type)
    {
        $timezone = config('services.time_zone');
        $time_arrive = null;
        $time_leaved = null;

        $time_duration = 0;

        if (!isset($record_list) || sizeof($record_list) > 0) {
            if ($student_type == '1' || $student_type == '3') {
                $time_arrive = $record_list->min('date_time');
                $day = Carbon::parse($time_arrive)->format('Y-m-d');
                // $time_leaved = sizeof($record_list) == 1 ? null : $record_list->max('leave_at');
            } else if ($student_type == '2') {
                $time_arrive = $record_list->min('record_time');
                // return $time_arrive;
                $day = Carbon::parse($time_arrive)->format('Y-m-d');
                // return $time_leaved;
                // $time_leaved = sizeof($record_list) == 1 ? null : $record_list->max('record_time');
            }
            $leave = Leave::where('user_id', $record_list->first()['user_id'])
                ->where('updated_at', 'like', '%' . $day . '%')
                ->where('status', true)
                ->first();
            $leave ? $time_leaved = $leave->updated_at->format('Y-m-d H:i:s') : $time_leaved = null;
        }

        if (isset($time_arrive) && isset($time_leaved)) {
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

        if (!isset($record_list) || sizeof($record_list) > 0) {
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
        $student_type = $this->student_school_type($user_id);
        $day = $request->input('day');
        $timezone = config('services.time_zone');

        $search_start_date = Carbon::parse($day, $timezone)->firstOfMonth()->toDateString() . " 00:00:00";
        $search_ended_date = Carbon::parse($day, $timezone)->lastOfMonth()->toDateString() . " 23:59:59";

        // $search_start_date = Carbon::parse($day, $timezone)->firstOfMonth();
        // $search_ended_date = Carbon::parse($day, $timezone)->lastOfMonth();

        $time_str = $this->date_record($student_type);
        $record_list = $this->record_list_check($student_type, $user_id, $time_str, $search_start_date, $search_ended_date);
        // return $record_list;
        $today_data_exis = $record_list->whereBetween($time_str, [
            Carbon::parse($day, $timezone)->toDateString() . " 00:00:00",
            Carbon::parse($day, $timezone)->toDateString() . " 23:59:59",
        ])
            ->whereBetween('updated_at', [
                Carbon::parse($day, $timezone)->toDateString() . " 00:00:00",
                Carbon::parse($day, $timezone)->toDateString() . " 23:59:59",
            ]);

        $record_group = $record_list->groupBy(function ($record) use ($timezone, $time_str) {
            return Carbon::parse($record->$time_str, $timezone)->format('Y-m-d');
        });

        $today_data = (count($today_data_exis) > 0) ? $record_group[$day] : [];

        $today_analysis = $this->analysis_day($today_data, $student_type);

        $today_work_hour = round($today_analysis['time_duration_seconds'] / 3600, 2);

        $array_start_date = [];
        $array_ended_date = [];
        $array_duration_seconds = [];

        foreach ($record_group as $current_day => $current_record_list) {
            $current_analysis = $this->analysis_day($current_record_list, $student_type);
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
            $array_secondes = collect($array_start_date)->map(function ($date_string) use ($timezone) {
                return Carbon::parse($date_string, $timezone)->diffInSeconds(Carbon::parse($date_string, $timezone)->startOfDay());
            })->toArray();

            $month_avg_time_arrive = gmdate('H:i:s', array_sum($array_secondes) / count($array_secondes));
        }
        if (sizeof($array_ended_date) > 0) {
            $array_secondes = collect($array_ended_date)->map(function ($date_string) use ($timezone) {
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
        $student_type = $this->student_school_type($user_id);
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

        $time_str = $this->date_record($student_type);
        $record_list = $this->record_list_check($student_type, $user_id, $time_str, $search_start_date, $search_ended_date);

        $record_group = $record_list->groupBy(function ($record) use ($timezone, $time_str) {
            return Carbon::parse($record->$time_str, $timezone)->format('Y-m-d');
        });

        $array_start_date = [];
        $array_ended_date = [];
        $array_duration_seconds = [];

        foreach ($record_group as $current_day => $current_record_list) {
            $current_analysis = $this->analysis_day($current_record_list, $student_type);
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
            $array_secondes = collect($array_start_date)->map(function ($date_string) use ($timezone) {
                return Carbon::parse($date_string, $timezone)->diffInSeconds(Carbon::parse($date_string, $timezone)->startOfDay());
            })->toArray();

            $week_avg_time_arrive = gmdate('H:i:s', array_sum($array_secondes) / count($array_secondes));
        }
        if (sizeof($array_ended_date) > 0) {
            $array_secondes = collect($array_ended_date)->map(function ($date_string) use ($timezone) {
                return Carbon::parse($date_string, $timezone)->diffInSeconds(Carbon::parse($date_string, $timezone)->startOfDay());
            })->toArray();

            $week_avg_time_leaved = gmdate('H:i:s', array_sum($array_secondes) / count($array_secondes));
        }
        if (sizeof($array_duration_seconds) > 0) {
            $week_sum_work_hour = round(array_sum($array_duration_seconds) / 3600, 2);
            $week_avg_work_hour = round(array_sum($array_duration_seconds) / count($array_duration_seconds) / 3600, 2);
        }
        $start_at = User::where('id', $user_id)->with('department:id,name,start_at')
            ->first()->department->start_at;
        $limit_H = Carbon::parse($start_at, $timezone)->format('H');
        $limit_i = Carbon::parse($start_at, $timezone)->format('i');
        foreach ($array_start_date as $current_day) {

            $current_time = Carbon::parse($current_day, $timezone);
            $limit_time = Carbon::parse($current_day, $timezone)->startOfDay()->addHours($limit_H)->addMinutes($limit_i);
            // if ($current_date > Carbon::parse($current_day, $timezone)->startOfDay()->addHours(9)) {
            //     $week_count_late += 1;
            // }
            if ($current_time->gt($limit_time)) {
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
        $student_type = $this->student_school_type($user_id);
        $sday = $request->input('sday');
        $eday = $request->input('eday');
        $timezone = config('services.time_zone');

        $search_start_date = Carbon::parse($sday, $timezone)->startOfDay();
        $search_ended_date = Carbon::parse($eday, $timezone)->endOfDay();
        $time_str = $this->date_record($student_type);
        $record_list = $this->record_list_check($student_type, $user_id, $time_str, $search_start_date, $search_ended_date);

        $record_group = $record_list->groupBy(function ($record) use ($timezone, $time_str) {
            return Carbon::parse($record->$time_str, $timezone)->format('Y-m-d');
        });

        $array_start_date = [];
        $array_ended_date = [];
        $array_duration_seconds = [];
        $workday_list = [];

        foreach ($record_group as $current_day => $current_record_list) {
            $current_analysis = $this->analysis_day($current_record_list, $student_type);
            $array_start_date[] = $current_analysis['time_arrive'];
            $array_ended_date[] = $current_analysis['time_leaved'];
            $array_duration_seconds[] = $current_analysis['time_duration_seconds'];
        }

        foreach ($record_group as $current_day => $current_record_list) {
            $current_analysis = $this->analysis_day($current_record_list, $student_type);
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
            $array_secondes = collect($array_start_date)->map(function ($date_string) use ($timezone) {
                return Carbon::parse($date_string, $timezone)->diffInSeconds(Carbon::parse($date_string, $timezone)->startOfDay());
            })->toArray();

            $month_avg_time_arrive = gmdate('H:i:s', array_sum($array_secondes) / count($array_secondes));
        }
        if (sizeof($array_ended_date) > 0) {
            $array_secondes = collect($array_ended_date)->map(function ($date_string) use ($timezone) {
                return Carbon::parse($date_string, $timezone)->diffInSeconds(Carbon::parse($date_string, $timezone)->startOfDay());
            })->toArray();

            $month_avg_time_leaved = gmdate('H:i:s', array_sum($array_secondes) / count($array_secondes));
        }
        if (sizeof($array_duration_seconds) > 0) {
            $month_sum_work_hour = round(array_sum($array_duration_seconds) / 3600, 2);
            $month_avg_work_hour = round(array_sum($array_duration_seconds) / count($array_duration_seconds) / 3600, 2);
        }

        $start_at = User::where('id', $user_id)->with('department:id,name,start_at')
            ->first()->department->start_at;
        $limit_H = Carbon::parse($start_at, $timezone)->format('H');
        $limit_i = Carbon::parse($start_at, $timezone)->format('i');

        foreach ($array_start_date as $current_day) {
            // $current_date = Carbon::parse($current_day, $timezone);
            // if ($current_date > Carbon::parse($current_day, $timezone)->startOfDay()->addHours(9)) {
            //     $month_count_late += 1;
            // }
            $current_time = Carbon::parse($current_day, $timezone);
            $limit_time = Carbon::parse($current_day, $timezone)->startOfDay()->addHours($limit_H)->addMinutes($limit_i);
            if ($current_time->gt($limit_time)) {
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
            ->where('check', true)
            ->get();

        // $record_group = $record_list->groupBy(function ($record) use ($timezone) {
        //     return Carbon::parse($record->record_time, $timezone)->format('Y-m-d');
        // });

        // $array_temperature_highest = [];
        // $array_temperature_lowest = [];
        // $temperature_val = null;
        // $record_time = null;
        // $recognition_name = null;
        // $created_at = null;

        // foreach ($record_list as $current_record) {
        //     if ($temperature_val == null || $temperature_val < (float) $current_record->temperature_val) {
        //         $temperature_val = (float) $current_record->temperature_val;
        //         $recognition_name = $current_record->recognition_name;
        //         $record_time = Carbon::parse($current_record->record_time, $timezone)->format('Y-m-d H:i:s');
        //         $created_at = Carbon::parse($current_record->created_at, $timezone)->format('Y-m-d H:i:s');
        //     }
        // }

        $output = [
            'user_id' => $user_id,
            'recognition_name' => $record_list->last()['recognition_name'],
            'temperature_val' => $record_list->last()['temperature_val'],
            'record_time' => Carbon::parse($record_list->last()['record_time'], $timezone)->format('Y-m-d H:i:s'),
            // 'created_at' => Carbon::parse($record_list->last()['created_at'], $timezone)->format('Y-m-d H:i:s'),
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
            ->where('check', true)
            ->get();

        $record_group = $record_list->groupBy(function ($record) use ($timezone) {
            return Carbon::parse($record->record_time, $timezone)->format('Y-m-d');
        });

        $array_temperature_highest = [];
        $array_temperature_lowest = [];

        foreach ($record_group as $current_day => $current_record_list) {
            $current_analysis = $this->analysis_temperatures($current_record_list);
            $array_temperature_lowest[] = $current_analysis['temperature_lowest'];
            $array_temperature_highest[] = $current_analysis['temperature_highest'];
        }
        foreach ($record_group as $current_day => $current_record_list) {
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
            ->where('check', true)
            ->get();

        $record_group = $record_list->groupBy(function ($record) use ($timezone) {
            return Carbon::parse($record->record_time, $timezone)->format('Y-m-d');
        });

        $array_temperature_highest = [];
        $array_temperature_lowest = [];

        $period = CarbonPeriod::between($sday, $eday);
        foreach ($period as $date) {
            $month_temperatures['d' . $date->day] = null;
        }

        foreach ($record_group as $current_day => $current_record_list) {
            $current_analysis = $this->analysis_temperatures($current_record_list);
            $array_temperature_lowest[] = $current_analysis['temperature_lowest'];
            $array_temperature_highest[] = $current_analysis['temperature_highest'];
        }
        foreach ($record_group as $current_day => $current_record_list) {
            $current_analysis = $this->analysis_temperatures($current_record_list);
            $current_date = Carbon::parse($current_day, $timezone);
            $month_temperatures['d' . $current_date->day] = $current_analysis['temperature_highest'];
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
    public function morning_temperatures(Request $request)
    {
        $user_id = (int) $request->input('user_id');
        $day = $request->input('day');
        $timezone = config('services.time_zone');

        $search_start_date = Carbon::parse($day, $timezone)->startOfDay();
        $search_ended_date = Carbon::parse($day, $timezone)->Midday();

        $record_list = Temperature::where('user_id', '=', $user_id)
            ->whereBetween('record_time', [$search_start_date, $search_ended_date])
            ->where('check', true)
            ->get();

        $output = [
            'user_id' => $user_id,
            'recognition_name' => $record_list->last()['recognition_name'],
            'temperature_val' => $record_list->last()['temperature_val'],
            'record_time' => Carbon::parse($record_list->last()['record_time'], $timezone)->format('Y-m-d H:i:s'),
            // 'created_at' => Carbon::parse($record_list->last()['created_at'], $timezone)->format('Y-m-d H:i:s'),
        ];

        return response()->json($output);
    }
    public function afternoon_temperatures(Request $request)
    {
        $user_id = (int) $request->input('user_id');
        $day = $request->input('day');
        $timezone = config('services.time_zone');

        $search_start_date = Carbon::parse($day, $timezone)->Midday();
        $search_ended_date = Carbon::parse($day, $timezone)->endOfDay();

        $record_list = Temperature::where('user_id', '=', $user_id)
            ->whereBetween('record_time', [$search_start_date, $search_ended_date])
            ->where('check', true)
            ->get();

        $output = [
            'user_id' => $user_id,
            'recognition_name' => $record_list->last()['recognition_name'],
            'temperature_val' => $record_list->last()['temperature_val'],
            'record_time' => Carbon::parse($record_list->last()['record_time'], $timezone)->format('Y-m-d H:i:s'),
            // 'created_at' => Carbon::parse($record_list->last()['created_at'], $timezone)->format('Y-m-d H:i:s'),
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

            $students_list = $this->get_parent_student($parents_id);
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

        $teacher_id = '';
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

            $students_list = $this->get_teacher_student($department_id);
        }

        $output = [
            'parentsid' => '',
            'teacherid' => $teacher_id,
            'phone' => $parents_phone,
            'name' => $teacher_name,
            'ename' => $teacher_ename,
            'relationship' => $teacher_relation,
            'school_id' => $school_id,
            'device_token' => $device_token,
            'students' => $students_list,
        ];


        return response()->json($output);
    }
    public function get_parent_student($parents_id)
    {
        $today = Carbon::today();
        $students_list = spu_relationship::where('parent_id', '=', $parents_id)
            ->with(['user' => function ($userQuery) use ($today) {
                $userQuery->with('department.teacher.profile')->with('profile')->with('user_type')
                    ->with(['contacts' => function ($contactQuery) use ($today) {
                        $contactQuery->where('onboard_date', $today)
                            ->select('id', 'onboard_date', 'user_id', 'status');
                    }])
                    ->with(['medicines' => function ($medicineQuery) use ($today) {
                        $medicineQuery->where('date', $today)
                            ->select('id', 'date', 'user_id', 'status');
                    }]);
            }])
            ->get();
        // return $students_list;
        $students_list = $students_list->map(function ($item) {
            $collection =  collect([
                'id' => $item->user->id,
                'name' => $item->user->profile->name,
                'avatar' => 'avatar/small/' . $item->user->profile->avatar,
                'mac' => $item->user->user_type->type_id == 2 || $item->user->user_type == null ? null : $item->user->user_type->mac->mac,
                'nick_name' => $item->user->profile->nickname,
                'gender' => $item->user->profile->gender,
                'birthday' => $item->user->profile->birthday,
                'onboard_date' => $item->user->onboard_date,
                'department_id' => $item->user->department == null ? null : $item->user->department->id,
                'classname' => $item->user->department == null ? null : $item->user->department->name,
                'supervisor_id' => $item->user->department == null ? null : $item->user->department->teacher->id,
                'teachername' => $item->user->department == null ? null : $item->user->department->teacher->profile->name,
                'start_at' => $item->user->department == null ? null : $item->user->department->start_at,
                'finish_at' => $item->user->department == null ? null : $item->user->department->finish_at,
                'contact_status' => $item->user->contacts->first() == null ? null : $item->user->contacts->first()->status,
                'medicine_status' => $item->user->medicines->first() == null ? null : $item->user->medicines->first()->status,
            ]);
            return $collection;
        });

        return $students_list;
    }
    public function get_teacher_student($department_id)
    {
        $today = Carbon::today();
        $students_list = User::whereIn('department_id', $department_id)
            ->with('profile')->with('user_type')->with('spu_relationship.parent')->with('department')
            ->with(['contacts' => function ($contactQuery) use ($today) {
                $contactQuery->where('onboard_date', $today)
                    ->select('id', 'onboard_date', 'user_id', 'status');
            }])
            ->with(['medicines' => function ($medicineQuery) use ($today) {
                $medicineQuery->where('date', $today)
                    ->select('id', 'date', 'user_id', 'status');
            }])
            ->get();

        $students_list = $students_list->map(function ($item) {
            $collection =  collect([
                'id' => $item->id,
                'name' => $item->profile->name,
                'avatar' => 'avatar/small/' . $item->profile->avatar,
                'mac' => $item->user_type->type_id == 2 || $item->user_type == null ? null : $item->user_type->mac->mac,
                'nick_name' => $item->profile->nickname,
                'gender' => $item->profile->gender,
                'birthday' => $item->profile->birthday,
                'onboard_date' => $item->onboard_date,
                'department_id' => $item->department->id,
                'classname' => $item->department->name,
                'supervisor_id' => $item->department->supervisor_id,
                'start_at' => $item->department->start_at,
                'finish_at' => $item->department->finish_at,
                'parent_id' => $item->spu_relationship == null ? null : $item->spu_relationship->parent->parent_id,
                'parent_name' => $item->spu_relationship == null ? null : $item->spu_relationship->parent->name,
                'contact_status' => $item->contacts->first() == null ? null : $item->contacts->first()->status,
                'medicine_status' => $item->medicines->first() == null ? null : $item->medicines->first()->status,
            ]);
            return $collection;
        });

        return $students_list;
    }
    public function refresh_parent_student(Request $request)
    {
        $parent_id = $request->parent_id;
        $students_list = $this->get_parent_student($parent_id);
        $output = [
            'students' => $students_list,
        ];

        return response()->json($output);
    }
    public function refresh_teacher_student(Request $request)
    {
        $teacher_id = $request->teacher_id;
        $department_id = User::find($teacher_id)->departments()->select('id')->get(); //array
        $students_list = $this->get_teacher_student($department_id);
        $output = [
            'students' => $students_list,
        ];

        return response()->json($output);
    }
    public function departmentIndex(Request $request)
    {
        $teacher_id = (int)$request->teacher_id;
        $departments = Department::where('supervisor_id', $teacher_id)->get();
        $departments = $departments->map(function ($item) {
            $collection =  collect([
                'department_id' => $item->id,
                'department_name' => $item->name,
            ]);
            return $collection;
        });

        return response()->json($departments);
    }

    // public function getAbsence(Request $request)
    // {
    //     $timezone = config('services.time_zone');
    //     $today = Carbon::now($timezone)->format('Y-m-d');
    //     // $today = '2020-10-19';
    //     // $late = Carbon::parse('10:00');
    //     $department_id = (int)$request->department_id;
    //     $student_type = Department::find($department_id)->school->student_type;
    //     $table_str = app(\App\Http\Controllers\DataController::class)->record_tempers($student_type);
    //     $time_str = $this->date_record($student_type);

    //     if ($student_type == '1' || $student_type == '3') {
    //         $students = User::where('department_id', $department_id)
    //             ->where("position_id", 10)->where('is_actived', true)
    //             ->with(['records' => function ($recordQuery) use ($today) {
    //                 $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
    //             }])
    //             ->get();
    //     } else if ($student_type == '2') {
    //         $students = User::where('department_id', $department_id)
    //             ->where("position_id", 10)->where('is_actived', true)
    //             ->with(['tempers' => function ($temperQuery) use ($today) {
    //                 $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
    //             }])
    //             ->get();
    //     }
    //     //absent students
    //     $students = $students->map(function ($student) use ($table_str, $time_str) {
    //         $collection =  collect([
    //             'id' => $student->id,
    //             'name' => $student->profile->name,
    //             'date_time' => $student->$table_str->first()[$time_str],
    //         ]);
    //         return $collection;
    //     })->filter(function ($student) {
    //         return $student['date_time'] == null;
    //     })->count();

    //     $allStudents = User::where('department_id', $department_id)
    //         ->where('position_id', 10)
    //         ->where('is_actived', 1)
    //         ->count();
    //     $attendance = round($students / $allStudents, 2) * 100;
    //     return [
    //         'students' => $students,
    //         'allStudents' => $allStudents,
    //         'attendance' => $attendance,
    //     ];
    // }
    // public function getErrTemp(Request $request)
    // {
    //     $department_id = (int)$request->department_id;
    //     $timezone = config('services.time_zone');
    //     $today = Carbon::now($timezone)->format('Y-m-d');

    //     $errorTemps = User::where('department_id', $department_id)
    //         ->where('position_id', 10)
    //         ->where('is_actived', 1)
    //         ->with('profile')
    //         ->with(['tempers' => function ($temperQuery) use ($today) {
    //             $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'desc');
    //         }])
    //         ->get();
    //     $errorTemps = $errorTemps->map(function ($errorTemp) {
    //         $collection =  collect([
    //             'id' => $errorTemp->tempers->first()['id'],
    //             'name' => $errorTemp->profile->name,
    //             'temperature_val' => $errorTemp->tempers->first()['temperature_val'],
    //             'user_id' => $errorTemp->tempers->first()['user_id'],
    //             'check' => $errorTemp->tempers->first()['check'],
    //         ]);
    //         return $collection;
    //     })->filter(function ($errorTemp) {
    //         return ((float) $errorTemp['temperature_val']) >= 37.5;
    //     });
    //     $errorTemps_count = $errorTemps->count();
    //     return response()->json(['data' => $errorTemps, 'count' => $errorTemps_count]);
    // }
    public function departmentInfo(Request $request)
    {
        $user_id = (int)$request->user_id;
        $department_id = (int)$request->department_id;
        if ($user_id) {
            $department_id = User::find($user_id)->department_id;
        }
        $department = Department::find($department_id);
        $absence_students = $this->getAbsence($department_id); //未到學生
        $allStudents = User::where('department_id', $department_id) //班級總學生
            ->where('position_id', 10)
            ->where('is_actived', 1)
            ->count();
        $arriveStudents = $allStudents - $absence_students;
        if ($arriveStudents != 0) {

            $errorTemps_count = $this->getErrTemp($department_id); //溫度異常學生
            $errorTemps_percent = round($errorTemps_count / $arriveStudents, 2) * 100;
        } else {
            $errorTemps_count = 0;
            $errorTemps_percent = 0;
        }
        $attendance = round($arriveStudents / $allStudents, 2) * 100; //出席率

        return [
            'department_id' => $department->id,
            'department_name' => $department->name,
            'errTempCount' => $errorTemps_count,
            'errTempPercent' => $errorTemps_percent,
            'absence' => $absence_students, //未到學生
            'attender' => $arriveStudents, //實到學生
            'allStudents' => $allStudents, //班級總學生
            'attendance' => $attendance,
        ];
    }

    public function getAbsence($department_id)
    {
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        // $today = '2020-10-19';
        // $late = Carbon::parse('10:00');
        // $department_id = (int)$request->department_id;
        $student_type = Department::find($department_id)->school->student_type;
        $table_str = app(\App\Http\Controllers\DataController::class)->record_tempers($student_type);
        $time_str = $this->date_record($student_type);

        if ($student_type == '1' || $student_type == '3') {
            $students = User::where('department_id', $department_id)
                ->where("position_id", 10)->where('is_actived', true)
                ->with(['records' => function ($recordQuery) use ($today) {
                    $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
                }])
                ->get();
        } else if ($student_type == '2') {
            $students = User::where('department_id', $department_id)
                ->where("position_id", 10)->where('is_actived', true)
                ->with(['tempers' => function ($temperQuery) use ($today) {
                    $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
                }])
                ->get();
        }
        //absent students
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
    public function getErrTemp($department_id)
    {
        // $department_id = (int)$request->department_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');

        $errorTemps = User::where('department_id', $department_id)
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
        return $errorTemps_count;
    }
}
