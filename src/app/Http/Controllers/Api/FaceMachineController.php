<?php

namespace App\Http\Controllers\Api;

use App\API\ApiHelper;
use App\API\ApiAchievement;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Machine;
use App\School;
use App\Record;
use App\Uuid;
use App\User;
use App\Temperature;
use Illuminate\Http\Request;

class FaceMachineController extends Controller
{
    use Apihelper;
    use ApiAchievement;

    public function indexMachineSerialNumber(Request $request)
    {
        $machines = Machine::all();

        $machines = $machines->filter(function ($machine) {
            return $machine->device_token === null;
        })->values()
            ->map(function ($machine) {
                $collection = collect([
                    'serial_number' => $machine->serial_number,
                ]);
                return $collection;
            });
        return $this->succeed($machines, 200);
    }
    public function updataMachineDeviceToken(Request $request)
    {
        $serial_number = $request->serial_number;
        $device_token = $request->device_token;
        Machine::where('serial_number', $serial_number)
            ->update([
                'device_token' => $device_token,
            ]);
        return $this->succeed('更新完成', 200);
    }
    // public function store(Request $request)
    // {
    //     $timezone = config('services.time_zone');
    //     $today = Carbon::now($timezone)->format('Y-m-d');
    //     $onboard_date = $today;

    //     $serial_number = $request->device_serial;
    //     $school_id = Machine::where('serial_number',$serial_number)->first()->school_id;
    //     $uuid = $request->uuid;
    //     $name = $request->name;
    //     $avatar = $request->avatar;
    //     $onboard_date = $today;

    //     $school = School::find($school_id);
    //     $user_add = new User([
    //         'department_id' =>  null,
    //         'position_id' => 10,
    //         'onboard_date' => $onboard_date,
    //     ]);
    //     $user_type_add = new UserType([
    //         'type_id' => 2,//type_id = 2 = face
    //     ]);
    //     $profile_add = new Profile([
    //         "name" => $name,
    //         "avatar" => $avatar,
    //         "gender" => 3,
    //     ]);
    //     $uuid_add = new Uuid([
    //         'uuid' => $uuid,
    //     ]);
    //     //save
    //     $school->users()->save($user_add)->user_type()->save($user_type_add)->uuid()->save($uuid_add);
    //     $user_add->profile()->save($profile_add);

    //     $data =  collect ( [
    //         'id' => $user_add->id,
    //         "person_id"     => "S".$user_add->id,
    //         "person_code"   => "S".$user_add->id,
    //     ]);

    //     return $data;
    // }

    public function temper(Request $request)
    {
        $record_time = $request->record_time;
        $uuid = $request->uuid;
        $temperature_val = $request->temperature_val;
        $user = Uuid::where('uuid', $uuid)->with('user_type.user.profile')->first();

        // 機器代碼
        $serial_number = $request->serial_number;

        // $machine = Machine::where('serial_number', $serial_number)->first();
        // $machine_school_id = $machine->school_id;

        $temper_add = new Temperature([
            'temperature_val' =>  $temperature_val,
            'recognition_name' => $user->user_type->user->profile->name,
            'record_time' => $record_time,
            'equipment_verification_id' => $serial_number,
            'school_id' => $user->user_type->user->school_id,
            'check' => ((float) $temperature_val) >= 37.5 ? false : true,
        ]);
        User::find($user->user_type->user->id)->tempers()->save($temper_add);

        $check = User::find($user->user_type->user->id);
        if ($check->department_id == null) { //身份為老師
            return $this->succeed('', 200);
        } else { //身份為學生
            $timezone = config('services.time_zone');
            $record_date = Carbon::parse($record_time, $timezone)->format('Y-m-d');
            $oneCheck = Temperature::where('user_id', $user->user_type->user->id)
                ->whereDate('record_time', $record_date)->get();
            if ($oneCheck->count() == 1) {
                //如果為第一次紀錄，判定有無遲到
                $this->onTime($user->user_type->user->id);
            }
            //每次都判定溫度
            $this->temperature($user->user_type->user->id);
            return $this->succeed('', 200);
        }
    }
    public function temperature($user_id)
    {

        $Achievement_str = 'getTemperature';
        // $user_id = (int) $request->user_id;
        $temperature_val = $this->day_temperature($user_id);

        if (((float) $temperature_val) <= 37.5 && $temperature_val != null) {
            $this->AchievementUpdate($user_id, $Achievement_str);
        }
        return $this->succeed('', 200);
    }
    public function onTime($user_id)
    {
        $Achievement_str = 'goToSchoolOnTime';
        // $user_id = (int) $request->user_id;
        $day_late = $this->day_late($user_id);
        if ($day_late == 0) {
            $this->AchievementUpdate($user_id, $Achievement_str);
        }

        return $this->succeed('', 200);
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
                $time_leaved = $record_list->max('leave_at');
            } else if ($student_type == '2') {
                $time_arrive = $record_list->min('record_time');
                $time_leaved = $record_list->max('record_time');
            }
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
        }
        return $record_list;
    }
    public function day_temperature($user_id)
    {
        // $user_id = (int) $request->input('user_id');
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');

        $search_start_date = Carbon::parse($today, $timezone)->startOfDay();
        $search_ended_date = Carbon::parse($today, $timezone)->endOfDay();

        $record_list = Temperature::where('user_id', '=', $user_id)
            ->whereBetween('record_time', [$search_start_date, $search_ended_date])
            ->where('check', true)
            ->get();

        return $record_list->last()['temperature_val'];

        // $output = [
        //     'user_id' => $user_id,
        //     'recognition_name' => $record_list->last()['recognition_name'],
        //     'temperature_val' => $record_list->last()['temperature_val'],
        //     'record_time' => Carbon::parse($record_list->last()['record_time'], $timezone)->format('Y-m-d H:i:s'),
        //     // 'created_at' => Carbon::parse($record_list->last()['created_at'], $timezone)->format('Y-m-d H:i:s'),
        // ];

        // return response()->json($output);
    }
    public function day_late($user_id)
    {
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        // $user_id = (int) $request->input('user_id');
        $student_type = $this->student_school_type($user_id);

        $search_start_date = Carbon::parse($today, $timezone)->firstOfMonth()->toDateString() . " 00:00:00";
        $search_ended_date = Carbon::parse($today, $timezone)->lastOfMonth()->toDateString() . " 23:59:59";

        $time_str = $this->date_record($student_type);
        $record_list = $this->record_list_check($student_type, $user_id, $time_str, $search_start_date, $search_ended_date);

        $today_data_exis = $record_list->whereBetween($time_str, [
            Carbon::parse($today, $timezone)->toDateString() . " 00:00:00",
            Carbon::parse($today, $timezone)->toDateString() . " 23:59:59",
        ]);

        $record_group = $record_list->groupBy(function ($record) use ($timezone, $time_str) {
            return Carbon::parse($record->$time_str, $timezone)->format('Y-m-d');
        });

        $today_data = (count($today_data_exis) > 0) ? $record_group[$today] : [];

        $today_analysis = $this->analysis_day($today_data, $student_type);
        $day_late = 0;
        $start_at = User::where('id', $user_id)->with('department:id,name,start_at')
            ->first()->department->start_at;
        $limit_H = Carbon::parse($start_at, $timezone)->format('H');
        $limit_i = Carbon::parse($start_at, $timezone)->format('i');
        $current_time = Carbon::parse($today_analysis['time_arrive'], $timezone);
        $limit_time = Carbon::parse($today, $timezone)->startOfDay()->addHours($limit_H)->addMinutes($limit_i);

        if ($current_time->gt($limit_time)) {
            $day_late = 1;
        }
        return $day_late;
        // $output = [
        //     'user_id' => $user_id,
        //     'date' => $today,
        //     "late" => $day_late,
        // ];

        // return response()->json($output);
    }
}
