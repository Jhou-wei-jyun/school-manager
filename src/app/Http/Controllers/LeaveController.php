<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\API\ApiHelper;
use App\Depart;
use App\Department;
use App\User;
use App\spu_relationship;
use App\Notify;
use App\Parents;
use App\Jobs\FCMNotification;
use App\Leave;
use App\Qrcode;

class LeaveController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        $department_id = (array)$request->department_id;
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

        $students = User::whereIn('department_id', $department_id)
            ->where("position_id", 10)->where("is_actived", true)->select('id')->pluck('id');
        $leaves = Leave::whereIn('user_id', $students)
            ->where('status', 1)
            ->where('created_at', 'like', '%' . $date . '%')
            ->get();
        // $notify = Notify::where('created_at', 'like', '%' . $date . '%')
        //     ->whereJsonContains(
        //         'relationship->id',
        //         25
        //     )
        //     ->first();
        // return $notify;
        $leaves = $leaves->map(function ($leave) use ($date) {
            $notify = Notify::where('created_at', 'like', '%' . $date . '%')
                ->whereJsonContains(
                    'relationship->id',
                    $leave->id
                )
                ->first();
            return [
                'id' => $leave->id,
                'student_name' => $leave->students->profile->name,
                'name' => $leave->name,
                'phone' => $leave->phone,
                'title' => $leave->title,
                // 'time' => Carbon::now()->format('H:m'),
                'time' => $leave->updated_at->totimestring(),
                // 'time' => Carbon::parse($leave->updated_at, "UTC")->format('H:m:s'),
                // 'status' => $leave->status,
                'sent_people' => $notify ? $notify->sent->profile->name : null,
            ];
        });
        return $this->succeed($leaves, 200);
    }
    public function orderindex(Request $request)
    {
        $department_id = (array)$request->department_id;
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

        $students = User::whereIn('department_id', $department_id)
            ->where("position_id", 10)->where("is_actived", true)->select('id')->pluck('id');
        $parents = spu_relationship::whereIn('user_id', $students)->pluck('parent_id')->unique();
        $qecodes = Qrcode::whereIn('parent_id', $parents)
            ->where('start_time', 'like', '%' . $date . '%')
            ->with('parent')
            ->get();
        $qecodes = $qecodes->map(function ($qecode) {
            $parmas = json_decode($qecode->parmas);
            $users = User::whereIn('id', $parmas->user_id_arr)->select('id')->with('profile:user_id,name')->get();
            return [
                'qrcode_id' => $qecode->qrcode_id,
                'name' => $users,
                'sent_people' => $parmas->other_people ? $parmas->other_people->name : $qecode->parent->name,
                'phone' => $parmas->other_people ? $parmas->other_people->phone : $qecode->parent->phone,
                'title' => $parmas->other_people ? $parmas->other_people->title : $qecode->parent->title,
            ];
        });
        return $this->succeed($qecodes, 200);
    }
}
