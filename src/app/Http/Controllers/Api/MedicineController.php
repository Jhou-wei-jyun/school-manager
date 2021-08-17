<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\API\ApiHelper;
use App\API\ImageCompress;
use App\Medicine;
use App\User;
use App\Department;
use App\Jobs\FCMNotification;
use App\Notify;
use App\Parents;
use App\Photo;
use App\Section;
use App\Setting;
use Carbon\Carbon;
use App\spu_relationship;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MedicineController extends Controller
{
    use ApiHelper;
    use ImageCompress;
    //origin
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $parent_id = $request->parent_id;
        $teacher_id = $request->teacher_id;
        $date = $request->date;
        $date_utc = Carbon::parse($date)->utc();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $date_tomorrow = Carbon::parse($date)->add(1, 'day')->toDateString();
        $carbon_now = Carbon::now();
        $now_h = $carbon_now->format('H');
        $now   = $carbon_now->format('H:i:s');
        $user = User::find($user_id);
        $range = Setting::where('school_id', $user->school_id)->first();
        $start = $range->contact_start_at;
        $end   = $range->contact_finish_at;
        $medicine = Medicine::where('user_id', $user_id)
            ->where('date', $date)
            ->with('photos')
            ->first();
        if ($parent_id && $now_h >= 12) { //家長12:00後操作移至明天
            $medicine = Medicine::where('user_id', $user_id)
                ->where('date', $date_tomorrow)
                ->with('photos')
                ->first();
        }
        $relation_parent = Parents::find(spu_relationship::where('user_id', $user_id)->first()->parent_id);
        $relation_teacher = User::find($user_id)->department->teacher;

        $photo_data = [];

        if (!($date_utc->gte($yesterday) && $date_utc->lte($today))) {
            //時間超過昨天跟今天只能index
            if ($medicine == null) {
                return [
                    'id' => null,
                    'name' => $user->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'date' => null,
                    'reason' => null,
                    'notation' => null,
                    'time' => null,
                    'pack' => null,
                    'cc' => null,
                    'other' => null,
                    'type' => null,
                    'checked' => 0,
                    'photo' => $photo_data,
                    'action' => 1, // 1:index, 2:edit, 3:家長編輯時間外
                ];
            } else {
                if (count($medicine->photos) != 0) {
                    $photo_data = $medicine->photos->map(function ($photo) {

                        $collection = collect([
                            "photo_id" => $photo->photo_id,
                            "large_path" => 'medicine/large/' . $photo->path,
                            "small_path" => 'medicine/small/' . $photo->path,
                            "created_at" => $photo->created_at,
                            "status" => $photo->status,
                        ]);
                        return $collection;
                    });
                }
                if ($parent_id  && $medicine->status == 2) {
                    Medicine::where('id', $medicine->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                //老師自己看不會重置
                if ($teacher_id  && $medicine->status == 1) {
                    Medicine::where('id', $medicine->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                return [
                    'id' => $medicine->id,
                    'name' => $medicine->user->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'date' => $medicine->date,
                    'reason' => $medicine->reason,
                    'notation' => $medicine->notation,
                    'time' => $medicine->time,
                    // 'time' => $medicine->time == null ? null : Carbon::parse($medicine->time)->format('H:i'),
                    'pack' => $medicine->pack,
                    'cc' => $medicine->cc,
                    'taken' => $medicine->taken,
                    'other' => $medicine->other,
                    'photo' => $photo_data,
                    'checked' => $medicine->checked,
                    'updated_at' => $medicine->updated_at,
                    'action' => 1, // 1:index, 2:edit, 3:家長編輯時間外
                ];
            }
        } else {
            if ($medicine == null) {
                if ($teacher_id) { //教師只能看
                    return [
                        'id' => null,
                        'name' => $user->profile->name,
                        'teacher_name' => $relation_teacher->profile->name,
                        'parent_name' => $relation_parent->name,
                        'date' => null,
                        'reason' => null,
                        'notation' => null,
                        'time' => null,
                        'pack' => null,
                        'cc' => null,
                        'taken' => null,
                        'other' => null,
                        'checked' => 0,
                        'photo' => $photo_data,
                        'action' => 1, // 1:index, 2:edit, 3:家長編輯時間外
                    ];
                }
                if ($parent_id) {

                    $student = User::where('id', $user_id)
                        ->where('position_id', 10)
                        ->where('is_actived', 1)
                        ->with('profile:user_id,name')
                        ->first();
                    //
                    $item = collect([
                        'user_id' => $student->id,
                        // 'parent_id' => $parent_id,
                        // 'teacher_id' => $student->department->teacher->id,
                        'school_id' => $student->school_id,
                        'date' => $now_h >= 12 ? $date_tomorrow : $date,
                    ]);
                    //抓到null，新增一筆空資料
                    //new contact
                    $medicine = new Medicine;
                    $medicine->user_id = $item->get('user_id');
                    // $medicine->parent_id = $item->get('parent_id');
                    // $medicine->teacher_id = $item->get('teacher_id');
                    $medicine->school_id = $item->get('school_id');
                    $medicine->date = $item->get('date');
                    $medicine->status = 0; //已讀重置
                    $medicine->save();
                    if ($now > $start && $now < $end) {
                        return [
                            'id' => $medicine->id,
                            'name' => $medicine->user->profile->name,
                            'teacher_name' => $relation_teacher->profile->name,
                            'parent_name' => $relation_parent->name,
                            'date' => $medicine->date,
                            'action' => 3, // 1:index, 2:edit, 3:家長編輯時間外
                        ];
                    }
                    return [
                        'id' => $medicine->id,
                        'name' => $medicine->user->profile->name,
                        'teacher_name' => $relation_teacher->profile->name,
                        'parent_name' => $relation_parent->name,
                        'date' => $medicine->date,
                        'action' => 2, // 1:index, 2:edit, 3:家長編輯時間外
                    ];
                }
            } else { //已經有新增過
                if (count($medicine->photos) != 0) {
                    $photo_data = $medicine->photos->map(function ($photo) {

                        $collection = collect([
                            "photo_id" => $photo->photo_id,
                            "large_path" => 'medicine/large/' . $photo->path,
                            "small_path" => 'medicine/small/' . $photo->path,
                            "created_at" => $photo->created_at,
                            "status" => $photo->status,
                        ]);
                        return $collection;
                    });
                }
                $student = User::where('id', $user_id)
                    ->where('position_id', 10)
                    ->where('is_actived', 1)
                    ->with('profile:user_id,name')
                    ->with('department.teacher')
                    ->first();

                if ($parent_id  && $medicine->status == 2) {
                    Medicine::where('id', $medicine->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                //老師自己看不會重置
                if ($teacher_id  && $medicine->status == 1) {
                    Medicine::where('id', $medicine->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                //家長限制時間內不能編輯
                if ($parent_id) {

                    // $now = "19:00:00";
                    // return $now < $start || $now > $end ? 1 : 2;
                    if ($now > $start && $now < $end) {
                        return [
                            'id' => $medicine->id,
                            'name' => $medicine->user->profile->name,
                            'teacher_name' => $relation_teacher->profile->name,
                            'parent_name' => $relation_parent->name,
                            'date' => $medicine->date,
                            'reason' => $medicine->reason,
                            'notation' => $medicine->notation,
                            'time' => $medicine->time,
                            // 'time' => $medicine->time == null ? null : Carbon::parse($medicine->time)->format('H:i'),
                            'pack' => $medicine->pack,
                            'cc' => $medicine->cc,
                            'taken' => $medicine->taken,
                            'other' => $medicine->other,
                            'photo' => $photo_data,
                            'checked' => $medicine->checked,
                            'updated_at' => $medicine->updated_at,
                            'action' => 3, // 1:index, 2:edit, 3:家長編輯時間外
                        ];
                    }
                }
                return [
                    'id' => $medicine->id,
                    'name' => $medicine->user->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'date' => $medicine->date,
                    'reason' => $medicine->reason,
                    'notation' => $medicine->notation,
                    'time' => $medicine->time,
                    // 'time' => $medicine->time == null ? null : Carbon::parse($medicine->time)->format('H:i'),
                    'pack' => $medicine->pack,
                    'cc' => $medicine->cc,
                    'taken' => $medicine->taken,
                    'other' => $medicine->other,
                    'photo' => $photo_data,
                    'checked' => $medicine->checked,
                    'updated_at' => $medicine->updated_at,
                    'action' => 2, // 1:index, 2:edit, 3:家長編輯時間外
                ];
            }
        }
    }
    public function update(Request $request)
    {
        $medicine_id = (int)$request->id;
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;
        $editColumn = (string)$request->editColumn;
        $edit = (string)$request->edit;
        if ($parent_id) {
            Medicine::where('id', $medicine_id)
                ->update([
                    $editColumn => $edit,
                    'status' => 1, //重置成家長已讀
                ]);
            return $this->succeed('', 200);
        }
        if ($teacher_id) {
            return $this->succeed('', 200);
        }

        return $this->error('parent_id or teacher_id undefined', 500);
    }
    public function listIndex(Request $request)
    {
        // $today = Carbon::today()->format('Y-m-d');
        $date = (string)$request->date; //Y-m-d
        $teacher_id = (int)$request->teacher_id;
        $users = User::whereIn('department_id', User::find($teacher_id)->departments->pluck('id'))->get();

        $relation_teacher = User::find($teacher_id);

        $medicines = Medicine::whereIn('user_id', $users->pluck('id'))
            ->whereDate('date', $date)
            ->whereNotNull('time')->whereNotNull('reason')
            ->with('user.profile')
            ->with('user.spu_relationship.parent')
            ->get();

        $medicines = $medicines->map(function ($medicine) use ($relation_teacher) {
            $collection = collect([
                "id" => $medicine->id,
                "date" => $medicine->date,
                "user_id" => $medicine->user_id,
                "name" => $medicine->user->profile->name,
                "teacher_id" => $relation_teacher->id,
                "teacher_name" => $relation_teacher->profile->name,
                "parent_id" => $medicine->user->spu_relationship->parent_id,
                "parent_name" => $medicine->user->spu_relationship->parent->name,
                "checked" => $medicine->checked,
                "status" => $medicine->status,
            ]);
            return $collection;
        });
        return $medicines;
    }
    public function listInfo(Request $request)
    {
        $medicine_id = (int)$request->medicine_id;
        $medicine = Medicine::find($medicine_id);
        $relation_parent = Parents::find(spu_relationship::where('user_id', $medicine->user_id)->first()->parent_id);
        $relation_teacher = User::find($medicine->user_id)->department->teacher;
        $photo_data = [];
        if (count($medicine->photos) != 0) {
            $photo_data = $medicine->photos->map(function ($photo) {

                $collection = collect([
                    "photo_id" => $photo->photo_id,
                    "large_path" => 'medicine/large/' . $photo->path,
                    "small_path" => 'medicine/small/' . $photo->path,
                    "created_at" => $photo->created_at,
                    "status" => $photo->status,
                ]);
                return $collection;
            });
        }
        return [
            'id' => $medicine->id,
            'user_id' => $medicine->user_id,
            'name' => $medicine->user->profile->name,
            "teacher_id" => $relation_teacher->id,
            "teacher_name" => $relation_teacher->profile->name,
            "parent_id" => $relation_parent->parent_id,
            "parent_name" => $relation_parent->name,
            'date' => $medicine->date,
            'reason' => $medicine->reason,
            'notation' => $medicine->notation,
            'time' => $medicine->time,
            // 'time' => $medicine->time == null ? null : Carbon::parse($medicine->time)->format('H:i'),
            'pack' => $medicine->pack,
            'cc' => $medicine->cc,
            'other' => $medicine->other,
            'photo' => $photo_data,
            'checked' => $medicine->checked,
            'updated_at' => $medicine->updated_at,
            'action' => 1, // 1:index, 2:edit, 3:家長編輯時間外
        ];
    }
    public function teacherCheck(Request $request)
    {
        $medicine_id = (int)$request->id;
        $teacher_id = (int)$request->teacher_id;
        if ($teacher_id) {
            Medicine::where('id', $medicine_id)
                ->update([
                    'checked' => true,
                    'status' => 2, //重置成老師已讀
                ]);
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

        return $this->error('teacher_id undefined', 500);
    }
    public function photo(Request $request)
    {
        $medicine_id = (int)$request->id;
        $parent_id = (int)$request->parent_id;
        $photo = (string)$request->photo;
        $validator = Validator::make(
            [
                'medicine_id' => $medicine_id,
                'parent_id' => $parent_id,
                'photo' => $photo,
            ],
            [
                'medicine_id' => 'required',
                'parent_id' => 'required',
                'photo' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }
        $ori_photos = Photo::where('imageable_type', 'App\Medicine')->where('imageable_id', $medicine_id)->get();
        if ($ori_photos->count() >= 2) {
            return $this->error('medicine photos is limited', 500);
        }

        $medicine =  Medicine::where('id', $medicine_id)->first();
        $timezone = config('services.time_zone');
        $current = Carbon::now($timezone)->timestamp;
        $storageFile = config('services.storage_file');

        $photo_name = "Medicine-" . $medicine->id . "-" . $current . ".jpg";
        $photo_path = $storageFile . 'medicine/' . $photo_name;
        $avatar_small_path = $storageFile . 'medicine/small/' . $photo_name;
        $avatar_medium_path = $storageFile . 'medicine/medium/' . $photo_name;
        $avatar_large_path = $storageFile . 'medicine/large/' . $photo_name;
        //base64 decode
        $extension = explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($photo, 0, strpos($photo, ',') + 1);
        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $photo);
        $image = str_replace(' ', '+', $image);
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        Storage::disk($storage)->makeDirectory($storageFile . 'medicine/small');
        Storage::disk($storage)->makeDirectory($storageFile . 'medicine/medium');
        Storage::disk($storage)->makeDirectory($storageFile . 'medicine/large');
        //
        Storage::disk('local')->put('medicine/' . $photo_name, base64_decode($image));
        // Storage::move('public/' . $photo_name, 'medicine/' . $photo_name);
        $this->compressSmallIMG($photo_path, $avatar_small_path);
        $this->compressMediumIMG($photo_path, $avatar_medium_path);
        $this->compressLargeIMG($photo_path, $avatar_large_path);
        $photo_add = new Photo([
            'path' => $photo_name,
        ]);
        $medicine->photos()->save($photo_add);

        return $this->succeed('', 200);
    }
    public function photoDelete(Request $request)
    {
        $storage = config('services.storage');
        $photo_id = (int)$request->photo_id;
        $ori_photo = Photo::find($photo_id);
        Storage::disk($storage)->delete('/medicine/' . $ori_photo->path);
        Storage::disk($storage)->delete('/medicine/small/' . $ori_photo->path);
        Storage::disk($storage)->delete('/medicine/medium/' . $ori_photo->path);
        Storage::disk($storage)->delete('/medicine/large/' . $ori_photo->path);
        $ori_photo->delete();

        return $this->succeed('', 200);
    }
    public function history(Request $request)
    {
        $user_id = $request->user_id;
        $month = $request->month;
        $medicine = Medicine::where('user_id', $user_id)
            ->whereMonth('date', $month)
            ->get();

        $medicines = $medicine->map(function ($medicine) {
            return [
                'id' => $medicine->id,
                'date' => Carbon::parse($medicine->date)->format('Y-m-d'),
            ];
        });
        return $medicines;
    }
    public function medicineNotify(Request $request)
    {
        $user_id = (int)$request->user_id;
        $parent_id = (int)$request->parent_id;
        $medicine_id = (int)$request->medicine_id;
        $type_message = 'normal';
        $type_sound = 'default';
        $statu = 10;
        $user = User::find($user_id);
        $relation_parent = Parents::find(spu_relationship::where('user_id', $user_id)->first()->parent_id);
        $relation_teacher = User::find($user_id)->department->teacher;

        $title = '[用藥通知]' . $user->profile->name;
        $message = 'from ' . $relation_parent->name;

        if ($parent_id) {

            $notify = new Notify;
            $notify->sent_id = $relation_parent->parent_id;
            $notify->sent_type = 'App\Parents';
            $notify->school_id = $relation_parent->school_id;
            $notify->title = $title;
            $notify->message = $message;
            $notify->target = '教師';
            $notify->statu_id = $statu;
            //對應類型JSON
            $notify->relationship = collect([
                'type' => 'medicine',
                'id' => $medicine_id,
                'user_id' => $user_id,
                'user_name' => $user->profile->name,
            ])->toJson();

            $token = $relation_teacher->device_token;

            //DB to save
            $notify->save();
            $notify->users()->attach($relation_teacher, ['status' => 0, 'student_id' => $user_id]);

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

        return $this->error('parent_id  undefined', 500);
    }

    //new
    public function new_listIndex(Request $request)
    {
        // $today = Carbon::today()->format('Y-m-d');
        $date = (string)$request->date; //Y-m-d
        $teacher_id = (int)$request->teacher_id;
        $users = User::whereIn('department_id', User::find($teacher_id)->departments->pluck('id'))->get();

        $relation_teacher = User::find($teacher_id);
        //過濾掉用藥時間及理由為Null的資料
        $medicines = Medicine::whereIn('user_id', $users->pluck('id'))
            ->whereDate('date', $date)
            ->whereNotNull('time')->whereNotNull('reason')
            ->with('user.profile')
            ->with('user.spu_relationship.parent')
            ->get();

        $medicines = $medicines->map(function ($medicine) use ($relation_teacher) {
            $collection = collect([
                "id" => $medicine->id,
                "date" => $medicine->date,
                "user_id" => $medicine->user_id,
                "name" => $medicine->user->profile->name,
                "teacher_id" => $relation_teacher->id,
                "teacher_name" => $relation_teacher->profile->name,
                "parent_id" => $medicine->user->spu_relationship->parent_id,
                "parent_name" => $medicine->user->spu_relationship->parent->name,
                "checked" => $medicine->checked,
                "status" => $medicine->status,
            ]);
            return $collection;
        });
        return $this->succeed($medicines, 200);
    }
    public function new_listInfo(Request $request)
    {
        $medicine_id = (int)$request->medicine_id;
        $medicine = Medicine::find($medicine_id);
        $relation_parent = Parents::find(spu_relationship::where('user_id', $medicine->user_id)->first()->parent_id);
        $relation_teacher = User::find($medicine->user_id)->department->teacher;
        $photo_data = [];
        if (count($medicine->photos) != 0) {
            $photo_data = $this->getMedicineAttachment($medicine);
        }
        return $this->succeed([
            'id' => $medicine->id,
            'user_id' => $medicine->user_id,
            'name' => $medicine->user->profile->name,
            "teacher_id" => $relation_teacher->id,
            "teacher_name" => $relation_teacher->profile->name,
            "parent_id" => $relation_parent->parent_id,
            "parent_name" => $relation_parent->name,
            'date' => $medicine->date,
            'reason' => $medicine->reason,
            'notation' => $medicine->notation,
            'time' => $medicine->time,
            // 'time' => $medicine->time == null ? null : Carbon::parse($medicine->time)->format('H:i'),
            'pack' => $medicine->pack,
            'cc' => $medicine->cc,
            'other' => $medicine->other,
            'photo' => $photo_data,
            'checked' => $medicine->checked,
            'updated_at' => $medicine->updated_at,
            'action' => 1, // 1:index, 2:edit, 3:家長編輯時間外
        ], 200);
    }
    public function new_teacherCheck(Request $request)
    {
        $medicine_id = (int)$request->id;
        $teacher_id = (int)$request->teacher_id;
        if ($teacher_id) {
            Medicine::where('id', $medicine_id)
                ->update([
                    'checked' => true,
                    'status' => 2, //重置成老師已讀
                ]);
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

            return $this->succeed('用藥完成', 200);
        }

        return $this->error('teacher_id undefined', 500);
    }

    public function new_history(Request $request)
    {
        $user_id = $request->user_id;
        $month = $request->month;
        $medicine = Medicine::where('user_id', $user_id)
            ->whereMonth('date', $month)
            ->get();

        $medicines = $medicine->map(function ($medicine) {
            return [
                'id' => $medicine->id,
                'date' => Carbon::parse($medicine->date)->format('Y-m-d'),
            ];
        });
        return $this->succeed($medicines, 200);
    }
    public function new_medicineNotify(Request $request)
    {
        $user_id = (int)$request->user_id;
        $parent_id = (int)$request->parent_id;
        $medicine_id = (int)$request->medicine_id;
        $type_message = 'normal';
        $type_sound = 'default';
        $statu = 10;
        $user = User::find($user_id);
        $relation_parent = Parents::find(spu_relationship::where('user_id', $user_id)->first()->parent_id);
        $relation_teacher = User::find($user_id)->department->teacher;

        $title = '[用藥通知]' . $user->profile->name;
        $message = 'from ' . $relation_parent->name;

        if ($parent_id) {

            $notify = new Notify;
            $notify->sent_id = $relation_parent->parent_id;
            $notify->sent_type = 'App\Parents';
            $notify->school_id = $relation_parent->school_id;
            $notify->title = $title;
            $notify->message = $message;
            $notify->target = '教師';
            $notify->statu_id = $statu;
            //對應類型JSON
            $notify->relationship = collect([
                'type' => 'medicine',
                'id' => $medicine_id,
                'user_id' => $user_id,
                'user_name' => $user->profile->name,
            ])->toJson();

            $token = $relation_teacher->device_token;

            //DB to save
            $notify->save();
            $notify->users()->attach($relation_teacher, ['status' => 0, 'student_id' => $user_id]);

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

            return $this->succeed('通知完成', 200);
        }

        return $this->error('parent_id  undefined', 500);
    }
    public function new_index(Request $request)
    {
        $user_id = $request->user_id;
        $parent_id = $request->parent_id;
        $teacher_id = $request->teacher_id;
        $date = $request->date;
        $date_utc = Carbon::parse($date)->utc();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $date_tomorrow = Carbon::parse($date)->add(1, 'day')->toDateString();
        $carbon_now = Carbon::now();
        $now_h = $carbon_now->format('H');
        $now   = $carbon_now->format('H:i:s');
        $user = User::find($user_id);
        $range = Setting::where('school_id', $user->school_id)->first();
        $start = $range->contact_start_at;
        $end   = $range->contact_finish_at;
        $medicine = Medicine::where('user_id', $user_id)
            ->where('date', $date)
            ->with('photos')
            ->first();
        if ($parent_id && $now_h >= 12) { //家長12:00後操作移至明天
            $medicine = Medicine::where('user_id', $user_id)
                ->where('date', $date_tomorrow)
                ->with('photos')
                ->first();
        }
        $relation_parent = Parents::find(spu_relationship::where('user_id', $user_id)->first()->parent_id);
        $relation_teacher = User::find($user_id)->department->teacher;

        $photo_data = [];

        if (!($date_utc->gte($yesterday) && $date_utc->lte($today))) {
            //時間超過昨天跟今天只能index
            if ($medicine == null) {
                return $this->succeed([
                    'id' => null,
                    'name' => $user->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'date' => null,
                    'reason' => null,
                    'notation' => null,
                    'time' => null,
                    'pack' => null,
                    'cc' => null,
                    'other' => null,
                    'type' => null,
                    'checked' => 0,
                    'photo' => $photo_data,
                    'action' => 1, // 1:index, 2:edit, 3:家長編輯時間外
                ], 200);
            } else {
                if (count($medicine->photos) != 0) {
                    $photo_data = $this->getMedicineAttachment($medicine);
                }
                if ($parent_id  && $medicine->status == 2) {
                    Medicine::where('id', $medicine->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                //老師自己看不會重置
                if ($teacher_id  && $medicine->status == 1) {
                    Medicine::where('id', $medicine->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                return $this->succeed([
                    'id' => $medicine->id,
                    'name' => $medicine->user->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'date' => $medicine->date,
                    'reason' => $medicine->reason,
                    'notation' => $medicine->notation,
                    'time' => $medicine->time,
                    // 'time' => $medicine->time == null ? null : Carbon::parse($medicine->time)->format('H:i'),
                    'pack' => $medicine->pack,
                    'cc' => $medicine->cc,
                    'taken' => $medicine->taken,
                    'other' => $medicine->other,
                    'photo' => $photo_data,
                    'checked' => $medicine->checked,
                    'updated_at' => $medicine->updated_at,
                    'action' => 1, // 1:index, 2:edit, 3:家長編輯時間外
                ], 200);
            }
        } else {
            if ($medicine == null) {
                if ($teacher_id) { //教師只能看
                    return [
                        'name' => $user->profile->name,
                        'teacher_name' => $relation_teacher->profile->name,
                        'parent_name' => $relation_parent->name,
                        'message' => 'no',
                        'action' => 1, // 1:index, 2:edit, 3:家長編輯時間外, 4:家長尚未創建
                    ];
                }
                if ($parent_id) {
                    $student = User::where('id', $user_id)
                        ->where('position_id', 10)
                        ->where('is_actived', 1)
                        ->with('profile:user_id,name')
                        ->first();
                    //
                    $item = collect([
                        'user_id' => $student->id,
                        'school_id' => $student->school_id,
                        'date' => $now_h >= 12 ? $date_tomorrow : $date,
                    ]);
                    //抓到null，新增一筆空資料
                    //new contact
                    $medicine = new Medicine;
                    $medicine->user_id = $item->get('user_id');
                    $medicine->school_id = $item->get('school_id');
                    $medicine->date = $item->get('date');
                    $medicine->status = 0; //已讀重置
                    $medicine->save();
                    if ($now > $start && $now < $end) {
                        return $this->succeed([
                            'id' => $medicine->id,
                            'name' => $medicine->user->profile->name,
                            'teacher_name' => $relation_teacher->profile->name,
                            'parent_name' => $relation_parent->name,
                            'date' => $medicine->date,
                            'reason' => null,
                            'notation' => null,
                            'time' => null,
                            'pack' => null,
                            'cc' => null,
                            'other' => null,
                            'type' => null,
                            'checked' => 0,
                            'action' => 3, // 1:index, 2:edit, 3:家長編輯時間外
                        ], 200);
                    }
                    return $this->succeed([
                        'id' => $medicine->id,
                        'name' => $medicine->user->profile->name,
                        'teacher_name' => $relation_teacher->profile->name,
                        'parent_name' => $relation_parent->name,
                        'date' => $medicine->date,
                        'reason' => null,
                        'notation' => null,
                        'time' => null,
                        'pack' => null,
                        'cc' => null,
                        'other' => null,
                        'type' => null,
                        'checked' => 0,
                        'action' => 2, // 1:index, 2:edit, 3:家長編輯時間外
                    ], 200);
                }
            } else { //已經有新增過
                if (count($medicine->photos) != 0) {
                    $photo_data = $this->getMedicineAttachment($medicine);
                }
                $student = User::where('id', $user_id)
                    ->where('position_id', 10)
                    ->where('is_actived', 1)
                    ->with('profile:user_id,name')
                    ->with('department.teacher')
                    ->first();

                if ($parent_id  && $medicine->status == 2) {
                    Medicine::where('id', $medicine->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                //老師自己看不會重置
                if ($teacher_id  && $medicine->status == 1) {
                    Medicine::where('id', $medicine->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                //家長限制時間內不能編輯
                if ($parent_id) {
                    // $now = "19:00:00";
                    // return $now < $start || $now > $end ? 1 : 2;
                    if ($now > $start && $now < $end) {
                        return $this->succeed([
                            'id' => $medicine->id,
                            'name' => $medicine->user->profile->name,
                            'teacher_name' => $relation_teacher->profile->name,
                            'parent_name' => $relation_parent->name,
                            'date' => $medicine->date,
                            'reason' => $medicine->reason,
                            'notation' => $medicine->notation,
                            'time' => $medicine->time,
                            // 'time' => $medicine->time == null ? null : Carbon::parse($medicine->time)->format('H:i'),
                            'pack' => $medicine->pack,
                            'cc' => $medicine->cc,
                            'taken' => $medicine->taken,
                            'other' => $medicine->other,
                            'photo' => $photo_data,
                            'checked' => $medicine->checked,
                            'updated_at' => $medicine->updated_at,
                            'action' => 3, // 1:index, 2:edit, 3:家長編輯時間外
                        ], 200);
                    }
                }
                return $this->succeed([
                    'id' => $medicine->id,
                    'name' => $medicine->user->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'date' => $medicine->date,
                    'reason' => $medicine->reason,
                    'notation' => $medicine->notation,
                    'time' => $medicine->time,
                    // 'time' => $medicine->time == null ? null : Carbon::parse($medicine->time)->format('H:i'),
                    'pack' => $medicine->pack,
                    'cc' => $medicine->cc,
                    'taken' => $medicine->taken,
                    'other' => $medicine->other,
                    'photo' => $photo_data,
                    'checked' => $medicine->checked,
                    'updated_at' => $medicine->updated_at,
                    'action' => 2, // 1:index, 2:edit, 3:家長編輯時間外
                ], 200);
            }
        }
    }
    public function getMedicineAttachment($medicine)
    {
        $photo_data = $medicine->photos->map(function ($photo) {

            $collection = collect([
                "photo_id" => $photo->photo_id,
                "large_path" => 'medicine/large/' . $photo->path,
                "small_path" => 'medicine/small/' . $photo->path,
                "created_at" => $photo->created_at,
                "status" => $photo->status,
            ]);
            return $collection;
        });

        return $photo_data;
    }
    public function new_update(Request $request)
    {
        $medicine_id = (int)$request->id;
        $parent_id = (int)$request->parent_id;
        // $teacher_id = (int)$request->teacher_id;
        $reason = $request->reason;
        $notation = $request->notation;
        $time = $request->time;
        $pack = $request->pack;
        $cc = $request->cc;
        $other = $request->other;
        $taken = $request->taken;
        $photo_edit = false;
        if ($parent_id) {
            if ($request->has('photo')) {
                $photo_arr = json_decode($request->photo);
                if (count($photo_arr) > 2) {
                    return $this->succeed('照片存取上限', 501);
                }
                foreach ($photo_arr as $photo) {
                    $validator = Validator::make(
                        [
                            'photo' => $photo,
                        ],
                        [
                            'photo' => 'required',
                        ]
                    );
                    if ($validator->fails()) {
                        return $this->errors('fails', $validator->errors(), 401);
                    }
                }
                $photo_edit = true;
            }
            if ($request->has('photo') && $photo_edit) {
                //確認完全符合數量及格式規則後，刪除所有舊檔案再新增
                $ori_photos = Photo::where('imageable_type', 'App\Medicine')->where('imageable_id', $medicine_id)->get();
                foreach ($ori_photos as $ori_photo) {
                    $this->deleteImage($ori_photo);
                }
                foreach ($photo_arr as $photo) {
                    $this->imageUplode($medicine_id, $photo);
                    sleep(1);
                }
            }
            Medicine::where('id', $medicine_id)
                ->update([
                    'reason' => $reason,
                    'notation' => $notation,
                    'time' => $time,
                    'pack' => $pack,
                    'cc' => $cc,
                    'other' => $other,
                    'taken' => $taken,
                    'status' => 1, //重置成家長已讀
                ]);
            return $this->succeed('更新完成', 200);
        }

        return $this->error('parent_id undefined', 500);
    }
    public function imageUplode($medicine_id, $photo)
    {
        $medicine =  Medicine::find($medicine_id);
        $timezone = config('services.time_zone');
        $current = Carbon::now($timezone)->timestamp;
        $storageFile = config('services.storage_file');

        $photo_name = "Medicine-" . $medicine->id . "-" . $current . ".jpg";
        $photo_path = $storageFile . 'medicine/' . $photo_name;
        $avatar_small_path = $storageFile . 'medicine/small/' . $photo_name;
        $avatar_medium_path = $storageFile . 'medicine/medium/' . $photo_name;
        $avatar_large_path = $storageFile . 'medicine/large/' . $photo_name;
        //base64 decode
        // $extension = explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($photo, 0, strpos($photo, ',') + 1);
        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $photo);
        $image = str_replace(' ', '+', $image);
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        Storage::disk($storage)->makeDirectory($storageFile . 'medicine/small');
        Storage::disk($storage)->makeDirectory($storageFile . 'medicine/medium');
        Storage::disk($storage)->makeDirectory($storageFile . 'medicine/large');
        //
        Storage::disk('local')->put('medicine/' . $photo_name, base64_decode($image));
        // Storage::move('public/' . $photo_name, 'medicine/' . $photo_name);
        $this->compressSmallIMG($photo_path, $avatar_small_path);
        $this->compressMediumIMG($photo_path, $avatar_medium_path);
        $this->compressLargeIMG($photo_path, $avatar_large_path);
        $photo_add = new Photo([
            'path' => $photo_name,
        ]);
        $medicine->photos()->save($photo_add);
    }
    public function deleteImage($ori_photo)
    {
        $storage = config('services.storage');
        Storage::disk($storage)->delete('/medicine/' . $ori_photo->path);
        Storage::disk($storage)->delete('/medicine/small/' . $ori_photo->path);
        Storage::disk($storage)->delete('/medicine/medium/' . $ori_photo->path);
        Storage::disk($storage)->delete('/medicine/large/' . $ori_photo->path);
        $ori_photo->delete();
    }
}
