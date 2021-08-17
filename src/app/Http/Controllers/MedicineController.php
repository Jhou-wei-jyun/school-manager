<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\API\ApiHelper;
use App\Medicine;
use App\Depart;
use App\Department;
use App\User;
use App\spu_relationship;
use App\Notify;
use App\Parents;
use App\Jobs\FCMNotification;

class MedicineController extends Controller
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
        // $date_utc = Carbon::parse($date)->utc();
        // $today = Carbon::today();
        // $yesterday = Carbon::yesterday();
        // $teacher = Department::where('id', $department_id)
        //     ->first();
        $students = User::whereIn('department_id', $department_id)
            ->where("position_id", 10)->where("is_actived", true)->select('id')->pluck('id');
        $medicines = Medicine::whereIn('user_id', $students)
            ->where('date', $date)
            ->with('photos')
            ->get();

        // $exist_student = $medicines->pluck('user_id');
        $medicines = $medicines->map(function ($medicine) {
            $photos = $medicine->photos->map(function ($photo) {
                return [
                    'name' => $photo->path,
                    'path' => 'medicine/' . $photo->path,
                    'small_path' => 'medicine/small/' . $photo->path,
                ];
            });
            return [
                'id' => $medicine->id,
                'name' => $medicine->user->profile->name,
                'date' => $medicine->date,
                'reason' => $medicine->reason,
                'notation' => $medicine->notation,
                'time' => $medicine->time,
                'pack' => $medicine->pack,
                'cc' => $medicine->cc,
                'taken' => $medicine->taken,
                'other' => $medicine->other,
                'checked' => $medicine->checked,
                'photos' => $photos,

            ];
        });
        return $this->succeed($medicines, 200);
    }
    public function check(Request $request)
    {
        $medicine_id = (int)$request->medicine_id;
        $teacher_id = $request->teacher_id;
        Medicine::where('id', $medicine_id)
            ->update([
                'checked' => true,
                'status' => 2, //重置成老師已讀
            ]);
        if ($teacher_id) {
            //寄出通知
            $medicine = Medicine::find($medicine_id);
            $type_message = 'normal';
            $type_sound = 'default';
            $statu = 10;
            $user = User::find($medicine->user_id);
            $relation_teacher = User::find($user->id)->department->teacher;
            $title = '[用藥完成]' . $user->profile->name;
            $message = 'from ' . $relation_teacher->profile->name;

            $relation_parent = Parents::find(spu_relationship::where('user_id', $medicine->user_id)->first()->parent_id);


            $notify = new Notify;
            $notify->sent_id = $teacher_id;
            $notify->sent_type = 'App\User';
            $notify->school_id = $relation_parent->school_id;
            $notify->title = $title;
            $notify->message = $message;
            $notify->target = '家長';
            $notify->statu_id = $statu;
            //對應類型JSON
            $notify->relationship = collect([
                'type' => 'medicine',
                'id' => $medicine_id,
                'user_id' => $user->id,
                'user_name' => $user->profile->name,
            ])->toJson();

            $token = $relation_parent->device_token;

            //DB to save
            $notify->save();
            $notify->parents()->attach($relation_parent, ['status' => 0, 'student_id' => $user->id]);

            $data = [
                'id' => $notify->id,
                'title' => $title,
                'message' => $message,
                'type' => $type_message,
                'token' => $token,
                'sound' => $type_sound,
                // 'system' => 'ios',
            ];

            $job = (new FCMNotification($data))->onConnection('redis_high');
            $this->dispatch($job);

            return $this->succeed('', 200);
        }
        return $this->succeed('', 200);
    }
}
