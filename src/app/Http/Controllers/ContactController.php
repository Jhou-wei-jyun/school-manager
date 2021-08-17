<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\API\ApiHelper;
use App\API\ImageCompress;
use App\API\StringFilter;
use App\Contact;
use App\Attendance;
use App\Depart;
use App\Department;
use App\File;
use App\Photo;
use App\User;
use App\Section;
use App\spu_relationship;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\AssignOp\Concat;

class ContactController extends Controller
{
    use ApiHelper;
    use ImageCompress;
    use StringFilter;

    public function foreachstore($not_exist, $date, $teacher, $students)
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
            $parent = spu_relationship::where('user_id', $value)->first();
            //新增一筆空資料
            //new contact
            $contact = new Contact;
            $contact->user_id = $student->id;
            $contact->name = $student->profile->name;
            $contact->temperature = $student->tempers->first()['temperature_val'];
            // $contact->parent_id = $parent->parent_id;
            // $contact->teacher_id = $teacher->supervisor_id;
            $contact->school_id = $student->school_id;
            $contact->onboard_date = $date;
            $contact->status = 0; //已讀重置
            $contact->save();
        }
        //重抓資料
        $contacts = Contact::whereIn('user_id', $students)
            ->where('onboard_date', $date)
            ->get();
        return $contacts;
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
        $date_utc = Carbon::parse($date)->utc();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $teacher = Department::where('id', $department_id)
            ->first();
        $students = User::where('department_id', $department_id)
            ->where("position_id", 10)->where("is_actived", true)->select('id')->pluck('id');
        $contacts = Contact::whereIn('user_id', $students)
            ->where('onboard_date', $date)
            ->with('files')
            ->with('photos')
            ->get();

        $exist_student = $contacts->pluck('user_id');
        $not_exist = $students->diff($exist_student);
        if (!($date_utc->gte($yesterday) && $date_utc->lte($today))) { //時間超過昨天跟今天只能index
            if (!$not_exist->isEmpty()) {
                $contacts = $this->foreachstore($not_exist, $date, $teacher, $students);
            }
            $contacts = $contacts->map(function ($contact) {
                $files = $contact->files->map(function ($file) {
                    return [
                        'name' => $file->path,
                        'path' => 'contact_file/' . $file->imageable_id . '/' . $file->path,
                    ];
                });
                $photos = $contact->photos->map(function ($photo) {
                    return [
                        'name' => $photo->path,
                        'path' => 'contact_file/' . $photo->path,
                        'small_path' => 'contact_file/small/' . $photo->path,
                    ];
                });
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'condition' => $contact->condition,
                    'return' => $contact->return,
                    'bring' => $contact->bring,
                    'mood' => $contact->mood,
                    'daily' => $contact->daily,
                    'to_parent' => $contact->to_parent,
                    'to_teacher' => $contact->to_teacher,
                    'files' => $files,
                    'photos' => $photos,

                ];
            });
        } else {
            if (!$not_exist->isEmpty()) {
                $contacts = $this->foreachstore($not_exist, $date, $teacher, $students);
            }
            $contacts = $contacts->map(function ($contact) {
                $files = $contact->files->map(function ($file) {
                    return [
                        'name' => $file->path,
                        'path' => 'contact_file/' . $file->imageable_id . '/' . $file->path,
                    ];
                });
                $photos = $contact->photos->map(function ($photo) {
                    return [
                        'name' => $photo->path,
                        'path' => 'contact_file/' . $photo->path,
                        'small_path' => 'contact_file/small/' . $photo->path,
                    ];
                });
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'condition' => $contact->condition,
                    'return' => $contact->return,
                    'bring' => $contact->bring,
                    'mood' => $contact->mood,
                    'daily' => $contact->daily,
                    'to_parent' => $contact->to_parent,
                    'to_teacher' => $contact->to_teacher,
                    'files' => $files,
                    'photos' => $photos,

                ];
            });
        }
        return $this->succeed($contacts, 200);
    }
    // public function update(Request $request)
    // {
    //     $admin_id = (int) $request->admin_id;
    //     $department_id = $request->department_id;
    //     $update_data = $request->update_data;
    //     $contact_id_arr = [];
    //     foreach ($update_data as $value) {
    //         $value = (object)$value;
    //         array_push($contact_id_arr, $value->id);
    //     }
    //     Contact::whereIn('id', $contact_id_arr)->update([
    //         "condition" => $value->condition,
    //         "return" => $value->return,
    //         "bring" => $value->bring,
    //         "daily" => $value->daily,
    //         "to_parent" => $value->to_parent,
    //         "to_teacher" => $value->to_teacher,
    //         'status' => 2, //重置成老師已讀
    //     ]);
    //     //log
    //     if ($admin_id) {
    //         activity()
    //             ->causedBy(Admin::find($admin_id))
    //             ->performedOn(Department::find($department_id))
    //             ->withProperties([
    //                 'type' => 'edit',
    //                 'result' => 'success',
    //             ])
    //             ->log('批量編輯班級聯絡簿');
    //     }

    //     return $this->succeed('', 200);
    // }
    public function getEdit(Request $request)
    {
        $id = $request->id;
        $contact =  Contact::find($id);
        return $this->succeed([
            'contact_id' => $contact->id,
            'name' => $contact->name,
            'condition' => $contact->condition === null ? [] : json_decode($contact->condition),
            'Return' => $contact->return === null ? [] : json_decode($contact->return),
            'bring' => $contact->bring === null ? [] : json_decode($contact->bring),
            'to_parent' => $contact->to_parent,
            'to_teacher' => $contact->to_teacher,
            'file' => $contact->files->first(),
            'file2' => $contact->files->get(1),
            'photo' => $contact->photos->first(),
            'photo2' => $contact->photos->get(1),
            'daily' => $contact->daily,
            'mood' => $contact->mood,
        ], 200);
    }
    public function edit(Request $request)
    {

        $admin_id = json_decode($request->admin_id);
        $contact_id = json_decode($request->contact_id);
        $contact = Contact::find($contact_id);
        $attendance = Attendance::where('user_id', $contact->user_id)
            ->where('date', $contact->onboard_date)->first();
        // Contact::where('id', $contact_id)
        $contact->update([
            "condition" => $attendance->leave === 1 ? $request->condition : null,
            "return" => $attendance->leave === 1 ? $request->Return : null,
            "bring" => $request->bring,
            "mood" => $attendance->leave === 1 ? $request->mood : null,
            "daily" => $request->daily,
            "to_parent" => $request->to_parent,
            'status' => 2, //重置成老師已讀
        ]);

        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn(Contact::where('id', $contact_id)->first())
                ->withProperties([
                    'type' => 'edit',
                    'result' => 'success',
                ])
                ->log('編輯聯絡簿');
        }
        return $this->succeed('', 200);
    }
    public function editFile(Request $request)
    {
        $admin_id = json_decode($request->admin_id);
        $contact_id = json_decode($request->contact_id);
        $file_count = 0;
        if ($request->has('file')) {
            $validator = Validator::make(
                [
                    'file' => $request->file,
                ],
                [
                    'file' => 'mimes:pdf,docx,doc,pptx,ppt,xlsx,xls',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('檔案格式不符', $validator->errors(), 401);
            }
            $file_count = $file_count + 1;
        }
        if ($request->has('file2')) {
            $validator = Validator::make(
                [
                    'file' => $request->file2,
                ],
                [
                    'file' => 'mimes:pdf,docx,doc,pptx,ppt,xlsx,xls',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('檔案格式不符', $validator->errors(), 401);
            }
            $file_count = $file_count + 1;
        }
        if ($request->has('photo')) {
            $validator = Validator::make(
                [
                    'photo' => $request->photo,
                ],
                [
                    'photo' => 'mimes:jpg,jpeg',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('檔案格式不符', $validator->errors(), 401);
            }
            $file_count = $file_count + 1;
        }
        if ($request->has('photo2')) {
            $validator = Validator::make(
                [
                    'photo' => $request->photo2,
                ],
                [
                    'photo2' => 'mimes:jpg,jpeg',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('檔案格式不符', $validator->errors(), 401);
            }
            $file_count = $file_count + 1;
        }
        if ($file_count > 2) {
            return $this->error('超過數量上限', 401);
        }
        $contacts = Contact::where('id', $contact_id)->get();
        $contact = $contacts[0]; //以第一筆為基礎

        //刪除所有舊檔案
        $this->deleteOldFile($contacts);
        $this->deleteOldPhoto($contacts);

        //上傳檔案第一筆檔案
        if ($request->has('file')) {
            $this->fileUpload($contact, $request->file);
        }
        if ($request->has('file2')) {
            $this->fileUpload($contact, $request->file2);
        }
        if ($request->has('photo')) {
            $this->photoUpload($contact, $request->photo);
        }
        if ($request->has('photo2')) {
            $this->photoUpload($contact, $request->photo2);
        }

        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($contact)
                ->withProperties([
                    'type' => 'edit',
                    'result' => 'success',
                ])
                ->log('編輯聯絡簿檔案');
        }
        return $this->succeed('', 200);
    }
    public function updateSync(Request $request)
    {
        $admin_id = json_decode($request->admin_id);
        $department_id = json_decode($request->department_id);
        $contact_id_arr = json_decode($request->contact_id_arr);
        $file_count = 0;
        if ($request->has('file')) {
            $validator = Validator::make(
                [
                    'file' => $request->file,
                ],
                [
                    'file' => 'mimes:pdf,docx,doc,pptx,ppt,xlsx,xls',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('檔案格式不符', $validator->errors(), 401);
            }
            $file_count = $file_count + 1;
        }
        if ($request->has('file2')) {
            $validator = Validator::make(
                [
                    'file' => $request->file2,
                ],
                [
                    'file' => 'mimes:pdf,docx,doc,pptx,ppt,xlsx,xls',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('檔案格式不符', $validator->errors(), 401);
            }
            $file_count = $file_count + 1;
        }
        if ($request->has('photo')) {
            $validator = Validator::make(
                [
                    'photo' => $request->photo,
                ],
                [
                    'photo' => 'mimes:jpg,jpeg',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('檔案格式不符', $validator->errors(), 401);
            }
            $file_count = $file_count + 1;
        }
        if ($request->has('photo2')) {
            $validator = Validator::make(
                [
                    'photo' => $request->photo2,
                ],
                [
                    'photo2' => 'mimes:jpg,jpeg',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('檔案格式不符', $validator->errors(), 401);
            }
            $file_count = $file_count + 1;
        }
        if ($file_count > 2) {
            return $this->error('超過數量上限', 401);
        }
        $contacts = Contact::whereIn('id', $contact_id_arr)->get();

        //刪除所有舊檔案
        $this->deleteOldFile($contacts);
        $this->deleteOldPhoto($contacts);
        //
        foreach ($contacts as $contact) {
            $attendance = Attendance::where('user_id', $contact->user_id)
                ->where('date', $contact->onboard_date)->first();
            // Contact::where('id', $contact_id)
            $contact->update([
                "condition" => $attendance->leave === 1 ? $request->condition : null,
                "return" => $attendance->leave === 1 ? $request->Return : null,
                "bring" => $request->bring,
                "mood" => $attendance->leave === 1 ? $request->mood : null,
                "daily" => $request->daily,
                'status' => 2, //重置成老師已讀
            ]);
        }
        $contacts = Contact::whereIn('id', $contact_id_arr)->get();
        $contact = Contact::find($contact_id_arr[0]); //以第一筆為基礎
        // Contact::whereIn('id', $contact_id_arr)->update([
        //     "condition" => $request->condition,
        //     "return" => $request->Return,
        //     "bring" => $request->bring,
        //     "mood" => $request->mood,
        //     "daily" => $request->daily,
        //     'status' => 2, //重置成老師已讀
        // ]);
        //上傳檔案第一筆檔案
        if ($request->has('file')) {
            $this->fileUpload($contact, $request->file);
        }
        if ($request->has('file2')) {
            $this->fileUpload($contact, $request->file2);
        }
        if ($request->has('photo')) {
            $this->photoUpload($contact, $request->photo);
        }
        if ($request->has('photo2')) {
            $this->photoUpload($contact, $request->photo2);
        }
        //同步第一筆資料
        $this->syncNewFile($contact, $contacts);
        $this->syncNewPhoto($contact, $contacts);

        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn(Department::find($department_id))
                ->withProperties([
                    'type' => 'edit',
                    'result' => 'success',
                ])
                ->log('批量編輯班級聯絡簿');
        }

        return $this->succeed('編輯成功', 200);
    }
    public function fileUpload($contact, $file)
    {

        //file
        $filenameWithExt    = $file->getClientOriginalName();
        $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension          = strtolower($file->getClientOriginalExtension());
        $filename = $this->ASCIIFilter($filename);
        $filename = $this->SpaceTo_($filename);
        $fileNameToStore    = $filename . '.' . $extension;

        $file_check = File::where('imageable_id', $contact->id)
            ->where('path', 'like', '%' . $filename . '%')->where('path', 'like', '%' . $extension . '%')
            ->get();
        if (!$file_check->isEmpty()) {
            if ($file_check[0]->path !== $fileNameToStore) {
                $fileNameToStore = $filename . '.' . $extension;
            } else {
                $fileNameToStore = $filename . '(' . $file_check->count() . ').' . $extension;
            }
        }
        Storage::putFileAs('contact_file/' . $contact->id, $file, $fileNameToStore);

        $file_add = new File([
            'path' => $fileNameToStore,
        ]);
        $contact->files()->save($file_add);
    }
    public function photoUpload($contact, $photo)
    {

        $timezone = config('services.time_zone');
        $current = Carbon::now($timezone)->timestamp;
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        $photo_name = "Contact-" . $contact->id . "-" . $current . ".jpg";
        $photo_path = $storageFile . 'contact_file/' . $photo_name;
        $avatar_small_path = $storageFile . 'contact_file/small/' . $photo_name;
        $avatar_medium_path = $storageFile . 'contact_file/medium/' . $photo_name;
        $avatar_large_path = $storageFile . 'contact_file/large/' . $photo_name;

        Storage::disk($storage)->makeDirectory($storageFile . 'contact_file/small');
        Storage::disk($storage)->makeDirectory($storageFile . 'contact_file/medium');
        Storage::disk($storage)->makeDirectory($storageFile . 'contact_file/large');
        //
        Storage::disk($storage)->putFileAs($storageFile . 'contact_file/', $photo, $photo_name);

        $this->compressSmallIMG($photo_path, $avatar_small_path);
        $this->compressMediumIMG($photo_path, $avatar_medium_path);
        $this->compressLargeIMG($photo_path, $avatar_large_path);
        $photo_add = new Photo([
            'path' => $photo_name,
        ]);
        $contact->photos()->save($photo_add);
        sleep(1);
    }
    public function deleteOldPhoto($contacts)
    {

        $storage = config('services.storage');
        foreach ($contacts as $contact) {
            foreach ($contact->photos as $photo) {
                $check_photos_count = Photo::where('path', $photo->path)->get(); //共用同一照片的
                if ($check_photos_count->count() === 1) {
                    Storage::disk($storage)->delete('/contact_file/' . $photo->path);
                    Storage::disk($storage)->delete('/contact_file/small/' . $photo->path);
                    Storage::disk($storage)->delete('/contact_file/medium/' . $photo->path);
                    Storage::disk($storage)->delete('/contact_file/large/' . $photo->path);
                }

                $photo->delete();
            }
        }
    }
    public function deleteOldFile($contacts)
    {
        $storage = config('services.storage');
        foreach ($contacts as $contact) {
            foreach ($contact->files as $file) {
                Storage::disk($storage)->delete('/contact_file/' . $file->imageable_id . '/' . $file->path);
                $file->delete();
            }
        }
    }
    public function syncNewPhoto($contact, $contacts)
    {
        foreach ($contact->photos as $photo) {
            foreach ($contacts as $item) {
                $item->photos()
                    ->firstOrCreate(
                        [
                            'path' => $photo->path,
                        ]
                    );
            }
        }
    }
    public function syncNewFile($contact, $contacts)
    {
        foreach ($contact->files as $file) {
            foreach ($contacts as $item) {

                $data = $item->files()
                    ->firstOrCreate(
                        [
                            'path' => $file->path,
                        ]
                    );
                if ($data->wasRecentlyCreated) {
                    Storage::copy('contact_file/' . $contact->id . '/' . $file->path, 'contact_file/' . $item->id . '/' . $file->path);
                }
            }
        }
    }
}
