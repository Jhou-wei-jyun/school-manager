<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\User;
use Carbon\Carbon;

class DepartmentStudentController extends Controller
{
    public function index_becon(Request $request)
    {
        $school_id = $request->school_id;
        $department_id = $request->department_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $students = School::find($school_id)
            ->users()
            ->where("position_id", 10)->where('is_actived', true)->where('department_id', $department_id)
            ->with('user_type.mac')
            ->with(['records' => function ($recordQuery) use ($today) {
                $recordQuery->where('records.date_time', 'like', '%' . $today . '%')->orderBy('id', 'asc');
            }])
            ->get();

        $students = $students->map(function ($student) {

            $collection =  collect([
                'id' => $student->id,
                'avatar' => 'avatar/small/' . $student->profile->avatar,
                'name' => $student->profile->name,
                'gender' => $student->profile->gender,
                'mac' => $student->user_type->mac == null ? null : $student->user_type->mac->mac,
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
        $department_id = $request->department_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $students = School::find($school_id)
            ->users()
            ->where("position_id", 10)->where('is_actived', true)->where('department_id', $department_id)
            ->with('user_type.uuid')
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
                'avatar' => 'avatar/small/' . $student->profile->avatar,
                'name' => $student->profile->name,
                'gender' => $student->profile->gender,
                'uuid' => $student->user_type->uuid == null ? null : $student->user_type->uuid->uuid,
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
        $department_id = $request->department_id;
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');
        $students = School::find($school_id)
            ->users()
            ->where("position_id", 10)->where('is_actived', true)->where('department_id', $department_id)
            ->with('user_type.mac')
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
                'avatar' => 'avatar/small/' . $student->profile->avatar,
                'name' => $student->profile->name,
                'gender' => $student->profile->gender,
                'mac' => $student->user_type->mac == null ? null : $student->user_type->mac->mac,
                'tempers' => $student->tempers->first()['temperature_val'],
                'date_time' => $student->records->first()['date_time'],
                'leave_at' => $student->records->last()['leave_at'],
            ]);

            return $collection;
        });

        return $students;
    }
}
