<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use App\Achievement;
use Illuminate\Http\Request;
use Route;
use Carbon\Carbon;
use App\API\ApiHelper;
use App\API\ApiAchievement;
use App\API\ImageCompress;
use App\API\StringFilter;
use App\Setting;
use App\Contact;
use App\User;
use App\Department;
use App\File;
use App\Jobs\FCMNotification;
use App\Notify;
use App\Parents;
use App\Photo;
use App\Section;
use App\spu_relationship;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ContactController extends Controller
{
    use ApiHelper;
    use ApiAchievement;
    use ImageCompress;
    use StringFilter;

    //all
    public function get_setting(Request $request)
    {
        $parent_id = $request->parent_id;
        $school_id = Parents::find($parent_id)->school_id;
        $setting = Setting::where('school_id', $school_id)->first();
        return [
            'start' => $setting->contact_start_at,
            'finish' => $setting->contact_finish_at,
        ];
    }
    function escape_like_str($str)
    {
        $like_escape_char = '!';

        return str_replace([$like_escape_char, '%', '_'], [
            $like_escape_char . $like_escape_char,
            $like_escape_char . '%',
            $like_escape_char . '_',
        ], $str);
    }
    public function returnContact($user_id)
    {
        $Achievement_str = 'returnContact';
        // $user_id = (int) $request->user_id;
        $this->AchievementUpdate($user_id, $Achievement_str);
    }
    //origin
    public function index(Request $request)
    {
        $user_id = (int)$request->user_id;
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;
        $date = (string)$request->date;
        $date_utc = Carbon::parse($date)->utc();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $student = User::where('id', $user_id)
            ->where('position_id', 10)
            ->where('is_actived', 1)
            ->with('profile:user_id,name')
            ->with(['tempers' => function ($temperQuery) use ($date) {
                $temperQuery->where('temperatures.record_time', 'like', '%' . $date . '%')->orderBy('id', 'desc');
            }])
            ->first();
        $contact = Contact::where('user_id', $user_id)
            ->where('onboard_date', $date)
            ->with('user.profile')
            ->first();


        $relation_parent = Parents::find(spu_relationship::where('user_id', $user_id)->first()->parent_id);
        $relation_teacher = User::find($user_id)->department->teacher;

        if (!($date_utc->gte($yesterday) && $date_utc->lte($today))) {
            //時間超過昨天跟今天只能index
            if ($contact == null) {
                return [
                    'name' => $student->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'message' => 'no',
                    'action' => 1, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                ];
            } else {
                if ($parent_id) {
                    $contact = Contact::where('user_id', $user_id)
                        ->where('onboard_date', $date)
                        ->with('user.profile')
                        ->where(function ($query) {
                            $query
                                ->where(function ($conditionQuery) {
                                    $conditionQuery
                                        ->whereNotNull('condition')
                                        ->where('condition', '!=', "[]");
                                })
                                ->orWhere(function ($returnQuery) {
                                    $returnQuery
                                        ->whereNotNull('return')
                                        ->where('return', '!=', "[]");
                                })
                                ->orWhere(function ($bringQuery) {
                                    $bringQuery
                                        ->whereNotNull('bring')
                                        ->where('bring', '!=', "[]");
                                })
                                ->orWhereNotNull('to_parent')->orWhereNotNull('to_teacher')
                                ->orWhereNotNull('daily')->orWhereNotNull('mood');
                        })
                        ->first();
                    // return $contact;
                    if ($contact === null) { //欄位全空缺當作沒有
                        return [
                            'message' => 'no',
                            'action' => 4, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                        ];
                    }
                }
                if ($parent_id  && $contact->status == 2) {
                    Contact::where('id', $contact->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                //老師自己看不會重置
                if ($teacher_id  && $contact->status == 1) {
                    Contact::where('id', $contact->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }

                return [
                    'id' => $contact->id,
                    'name' => $contact->user->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'temperature' => $contact->temperature,
                    'condition' => $contact->condition,
                    'return' => $contact->return,
                    'bring' => $contact->bring,
                    'daily' => $contact->daily,
                    'to_parent' => $contact->to_parent,
                    'to_teacher' => $contact->to_teacher,
                    'mood' => $contact->mood,
                    'updated_at' => $contact->updated_at,
                    'message' => 'old',
                    'action' => 1, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                ];
            }
        } else {
            if ($contact == null) {
                if ($parent_id) { //家長只能看
                    return [
                        'message' => 'no',
                        'action' => 4, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                    ];
                }
                if ($teacher_id) {
                    //get student temper & parent



                    //
                    $item = collect([
                        'user_id' => $student->id,
                        'user_name' => $student->profile->name,
                        'temperature' => $student->tempers->first()['temperature_val'],
                        // 'parent_id' => $relation_parent->parent_id,
                        // 'teacher_id' => $relation_teacher->id,
                        'school_id' => $student->school_id,
                        'onboard_date' => $date,
                    ]);
                    //抓到null，新增一筆空資料
                    //new contact
                    $contact = new Contact;
                    $contact->user_id = $item->get('user_id');
                    $contact->name = $item->get('user_name');
                    $contact->temperature = $item->get('temperature');
                    // $contact->parent_id = $item->get('parent_id');
                    // $contact->teacher_id = $item->get('teacher_id');
                    $contact->school_id = $item->get('school_id');
                    $contact->onboard_date = $item->get('onboard_date');
                    $contact->status = 0; //已讀重置
                    $contact->save();

                    return [
                        'id' => $contact->id,
                        'temperature' => $contact->temperature,
                        'name' => $student->profile->name,
                        'teacher_name' => $relation_teacher->profile->name,
                        'parent_name' => $relation_parent->name,
                        'message' => 'new',
                        'action' => 2, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                    ];
                }
            } else { //已經有新增過


                if ($parent_id  && $contact->status == 2) {
                    Contact::where('id', $contact->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                //老師自己看不會重置
                if ($teacher_id  && $contact->status == 1) {
                    Contact::where('id', $contact->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }


                if ($parent_id) {
                    $contact = Contact::where('user_id', $user_id)
                        ->where('onboard_date', $date)
                        ->with('user.profile')
                        ->where(function ($query) {
                            $query
                                ->where(function ($conditionQuery) {
                                    $conditionQuery
                                        ->whereNotNull('condition')
                                        ->where('condition', '!=', "[]");
                                })
                                ->orWhere(function ($returnQuery) {
                                    $returnQuery
                                        ->whereNotNull('return')
                                        ->where('return', '!=', "[]");
                                })
                                ->orWhere(function ($bringQuery) {
                                    $bringQuery
                                        ->whereNotNull('bring')
                                        ->where('bring', '!=', "[]");
                                })
                                ->orWhereNotNull('to_parent')->orWhereNotNull('to_teacher')
                                ->orWhereNotNull('daily')->orWhereNotNull('mood');
                        })
                        ->first();
                    // return $contact;
                    if ($contact === null) { //欄位全空缺當作沒有
                        return [
                            'message' => 'no',
                            'action' => 4, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                        ];
                    }
                    //家長限制時間內不能編輯
                    $range = Setting::where('school_id', $contact->school_id)->first();
                    $start = $range->contact_start_at;
                    $end   = $range->contact_finish_at;
                    $now   = Carbon::now()->format('H:i:s');
                    // $now = "19:00:00";
                    // return $now < $start || $now > $end ? 1 : 2;
                    if ($now > $start && $now < $end) {
                        return [
                            'id' => $contact->id,
                            'name' => $contact->user->profile->name,
                            'teacher_name' => $relation_teacher->profile->name,
                            'parent_name' => $relation_parent->name,
                            'temperature' => $student->tempers->first()['temperature_val'],
                            'condition' => $contact->condition,
                            'return' => $contact->return,
                            'bring' => $contact->bring,
                            'daily' => $contact->daily,
                            'to_parent' => $contact->to_parent,
                            'to_teacher' => $contact->to_teacher,
                            'mood' => $contact->mood,
                            'updated_at' => $contact->updated_at,
                            'message' => 'old',
                            'action' => 3, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                        ];
                    }
                }
                return [
                    'id' => $contact->id,
                    'name' => $contact->user->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'temperature' => $student->tempers->first()['temperature_val'],
                    'condition' => $contact->condition,
                    'return' => $contact->return,
                    'bring' => $contact->bring,
                    'daily' => $contact->daily,
                    // 'section_id' => $contact->sections->get('id'),
                    // 'section_name' => $contact->sections->get('name'),
                    'to_parent' => $contact->to_parent,
                    'to_teacher' => $contact->to_teacher,
                    'mood' => $contact->mood,
                    'updated_at' => $contact->updated_at,
                    'message' => 'old',
                    'action' => 2, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                    // 'onboard_date' => $contact->onboard_date,
                ];
            }
        }
    }
    public function update(Request $request)
    {
        $parent_id = $request->parent_id;
        $teacher_id = $request->teacher_id;
        $temperature = $request->temperature;
        if ($parent_id) {
            // $sections = Section::whereIn('id',$request->section_id)->get();
            Contact::where('id', $request->id)
                ->update([
                    "temperature" => $temperature,
                    'to_teacher' => $request->to_teacher,
                    'status' => 1, //重置成家長已讀
                ]);
            return $this->succeed('', 200);
            // $contact = Contact::find($request->id);
            // $contact->sections()->detach();
            // $contact->sections()->attach($sections);
        }
        if ($teacher_id) {
            // $sections = Section::whereIn('id',$request->section_id)->get();
            Contact::where('id', $request->id)
                ->update([
                    "temperature" => $temperature,
                    'condition' => $request->condition,
                    'return' => $request->return,
                    'bring' => $request->bring,
                    'daily' => $request->daily,
                    'to_parent' => $request->to_parent,
                    'status' => 2, //重置成老師已讀
                ]);
            return $this->succeed('', 200);
            // $contact = Contact::find($request->id);
            // $contact->sections()->detach();
            // $contact->sections()->attach($sections);
        }

        return $this->error('parent_id or teacher_id undefined', 250);
    }
    public function history(Request $request)
    {
        $user_id = $request->user_id;
        $month = $request->month;
        $contacts = Contact::where('user_id', $user_id)
            ->whereMonth('onboard_date', $month)
            ->get();

        $contacts = $contacts->map(function ($contact) {
            return [
                'id' => $contact->id,
                'onboard_date' => Carbon::parse($contact->onboard_date)->format('Y-m-d'),
                'updated_at' => $contact->updated_at,
                'status' => $contact->status, //0:雙方未讀, 1:家長已讀, 2:老師已讀, 3:雙方已讀,
                'message' => 'old',
            ];
        });
        return $contacts;
    }

    public function selectContact(Request $request)
    {
        //0:雙方未讀, 1:家長已讀, 2:老師已讀, 3:雙方已讀,
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;

        $filter = json_decode($request->filter, true);

        $keyword = (string)$filter['keyword'];
        $date = (string)$filter['date'];
        $status = $filter['status'] === null ? null : (int)$filter['status']; //0 or 1

        if ($parent_id) {
            $users = User::whereIn('id', spu_relationship::where('parent_id', $parent_id)->pluck('user_id'))->get();

            if ($status === 0) {
                $info_status = [0, 1];
            } else if ($status === 1) {
                $info_status = [2, 3];
            } else {
                $info_status = [0, 1, 2, 3];
            }
            $contacts = Contact::whereIn('user_id', $users->pluck('id'))->where('name', 'like', "%" . $this->escape_like_str($keyword) . "%")
                ->where(function ($query) use ($date, $status, $info_status) { //符合2者
                    $query->where('onboard_date', $date == null ? '!=' : '=', $date)->whereIn('status', $info_status);
                })
                ->where(function ($query) {
                    $query
                        ->whereNotNull('condition')
                        ->orWhereNotNull('return')->orWhereNotNull('bring')
                        ->orWhereNotNull('to_parent')->orWhereNotNull('to_teacher')
                        ->orWhereNotNull('daily')->orWhereNotNull('mood');
                })
                ->get();
        }

        if ($teacher_id) {
            $users = User::whereIn('department_id', User::find($teacher_id)->departments->pluck('id'))->get();
            if ($status === 0) {
                $info_status = [0, 2];
            } else if ($status === 1) {
                $info_status = [1, 3];
            } else {
                $info_status = [0, 1, 2, 3];
            }
            $contacts = Contact::whereIn('user_id', $users->pluck('id'))->where('name', 'like', "%" . $this->escape_like_str($keyword) . "%")
                ->where(function ($query) use ($date, $status, $info_status) { //符合2者
                    $query->where('onboard_date', $date == null ? '!=' : '=', $date)->whereIn('status', $info_status);
                })
                ->where(function ($query) {
                    $query
                        ->whereNotNull('condition')
                        ->orWhereNotNull('return')->orWhereNotNull('bring')
                        ->orWhereNotNull('to_parent')->orWhereNotNull('to_teacher')
                        ->orWhereNotNull('daily')->orWhereNotNull('mood');
                })
                ->get();
        }
        return $contacts;
    }

    public function updateNew(Request $request)
    {
        $contact_id = (int)$request->id;
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;
        $editColumn = (string)$request->editColumn;
        $edit = (string)$request->edit;
        if ($parent_id) {
            $contact =  Contact::where('id', $contact_id)->first();
            $contact->update([
                $editColumn => $edit,
                'status' => 1, //重置成家長已讀
            ]);
            $this->returnContact($contact->user_id);
            return $this->succeed('', 200);
        }
        if ($teacher_id) {
            Contact::where('id', $contact_id)
                ->update([
                    $editColumn => $edit,
                    'status' => 2, //重置成老師已讀
                ]);
            return $this->succeed('', 200);
        }

        return $this->error('parent_id or teacher_id undefined', 250);
    }
    public function updateSync(Request $request)
    {
        $contact_id = (int)$request->id;
        $user_id = (int)$request->user_id;
        $department = User::find($user_id)->department;
        $contact = Contact::find($contact_id);
        $users = User::where('department_id', $department->id)->select('id')
            ->with(['profile' => function ($Query) {
                $Query->select('user_id', 'name');
            }])
            ->with(['spu_relationship' => function ($Query) {
                $Query->select('user_id', 'parent_id');
            }])
            ->get();

        foreach ($users as $user) {
            $user->contacts()
                ->updateOrCreate(
                    [
                        'onboard_date' => $contact->onboard_date,
                    ],
                    [
                        'name' => $user->profile->name,
                        'condition' => $contact->condition,
                        'return' => $contact->return,
                        'bring' => $contact->bring,
                        'daily' => $contact->daily,
                        // 'teacher_id' => $department->supervisor_id,
                        // 'parent_id' => $user->spu_relationship->parent_id,
                        'school_id' => $department->school_id,
                        'mood' => $contact->mood,
                        // 'files_name' => $contact->files_name,
                        // 'photos_name' => $contact->photos_name,
                        'status' => 2, //重置成老師已讀
                    ]
                );
        }
        $contacts = Contact::where('onboard_date', $contact->onboard_date)
            ->whereIn('user_id', $users->pluck('id'))
            ->get();

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
        return $this->succeed('', 200);
    }

    public function contactNotify(Request $request)
    {
        $user_id = (int)$request->user_id;
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;
        $contact_id = (int)$request->contact_id;
        $type_message = 'normal';
        $type_sound = 'default';
        $statu = 10;
        $user = User::find($user_id);
        $user_name = $user->profile->name;

        $title = '[聯絡簿]' . $user_name;
        $relation_parent = Parents::find(spu_relationship::where('user_id', $user_id)->first()->parent_id);
        $relation_teacher = User::find($user_id)->department->teacher;

        if ($parent_id) {
            $message = 'from ' . $relation_parent->name;

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
                'type' => 'contact',
                'id' => $contact_id,
                'user_id' => $user_id,
                'user_name' => $user_name,
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
        if ($teacher_id) {
            $message = 'from ' . $relation_teacher->profile->name;

            $notify = new Notify;
            $notify->sent_id = $relation_teacher->id;
            $notify->sent_type = 'App\User';
            $notify->school_id = $relation_teacher->school_id;
            $notify->title = $title;
            $notify->message = $message;
            $notify->target = '家長';
            $notify->statu_id = $statu;
            //對應類型JSON
            $notify->relationship = collect([
                'type' => 'contact',
                'id' => $contact_id,
                'user_id' => $user_id,
                'user_name' => $user_name,
            ])->toJson();

            $token = $relation_parent->device_token;

            //DB to save
            $notify->save();
            $notify->parents()->attach($relation_parent, ['status' => 0, 'student_id' => $user_id]);

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

        return $this->error('parent_id or teacher_id  undefined', 250);
    }
    public function getMood(Request $request)
    {
        $user_id = (int)$request->user_id;
        $date = (string)$request->date;
        $contact = Contact::where('user_id', $user_id)
            ->where('onboard_date', $date)
            ->with('user.profile')
            ->first();
        if ($contact == null) {
            return [
                'contact_id' => null,
                'mood' => null,
            ];
        } else {
            return [
                'contact_id' => $contact->id,
                'mood' => $contact->mood,
            ];
        }
    }
    public function contactInfo(Request $request)
    {
        $contact_id = (int)$request->contact_id;
        $contact = Contact::find($contact_id);

        $relation_parent = Parents::find(spu_relationship::where('user_id', $contact->user_id)->first()->parent_id);
        $relation_teacher = User::find($contact->user_id)->department->teacher;

        return [
            'id' => $contact->id,
            'user_id' => $contact->user_id,
            'name' => $contact->user->profile->name,
            'teacher_name' => $relation_teacher->profile->name,
            'parent_name' => $relation_parent->name,
            'temperature' => $contact->temperature_val,
            'condition' => $contact->condition,
            'return' => $contact->return,
            'bring' => $contact->bring,
            'daily' => $contact->daily,
            'to_parent' => $contact->to_parent,
            'to_teacher' => $contact->to_teacher,
            'updated_at' => $contact->updated_at,
            'action' => 2, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
        ];
    }

    public function getContactAttachment(Request $request)
    {
        $contact_id = (int)$request->contact_id;
        $contact = Contact::find($contact_id);
        $photos = $contact->photos;
        $files = $contact->files;
        $photos = $photos->map(function ($photo) {
            $collection = collect([
                'id' => $photo->photo_id,
                'path' => 'contact_file/small/' . $photo->path,
            ]);
            return $collection;
        });
        $files = $files->map(function ($file) {
            $collection = collect([
                'id' => $file->file_id,
                'path' => 'contact_file/' . $file->imageable_id . '/' . $file->path,
            ]);
            return $collection;
        });

        return [
            'not_images' => $files,
            'images' => $photos
        ];
    }
    public function deleteImage(Request $request)
    {
        $photo_id = (int)$request->id;
        $photo = Photo::find($photo_id);
        $photos = Photo::where('path', $photo->path)->get(); //共用同一照片的
        $storage = config('services.storage');
        if ($photos->count() === 1) {
            Storage::disk($storage)->delete('/contact_file/' . $photo->path);
            Storage::disk($storage)->delete('/contact_file/small/' . $photo->path);
            Storage::disk($storage)->delete('/contact_file/medium/' . $photo->path);
            Storage::disk($storage)->delete('/contact_file/large/' . $photo->path);
        }
        $photo->delete();
        return $this->succeed('圖片移除成功', 200);
    }
    public function deleteNotImage(Request $request)
    {
        $file_id = (int)$request->id;
        $file = File::find($file_id);
        $files = File::where('path', $file->path)->get(); //共用同一檔案的
        $storage = config('services.storage');
        Storage::disk($storage)->delete('/contact_file/' . $file->imageable_id . '/' . $file->path);
        $file->delete();
        return $this->succeed('檔案移除成功', 200);
    }

    public function notImageUplode(Request $request)
    {
        $contact_id = (int)$request->contact_id;
        $file = $request->file('file');
        $validator = Validator::make(
            [
                'file' => $file,
            ],
            [
                'file' => 'required|mimes:pdf,docx,doc,pptx,ppt,xlsx,xls',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('檔案格式不符(pdf,doc,xls,ppt)', $validator->errors(), 401);
        }
        $ori_files = File::where('imageable_type', 'App\Contact')->where('imageable_id', $contact_id)->get();
        if ($ori_files->count() >= 2) {
            return $this->error('檔案存取上限', 101);
        }
        $contact = Contact::find($contact_id);
        if ($request->hasFile('file')) {
            $filenameWithExt    = $file->getClientOriginalName();
            $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension          = strtolower($file->getClientOriginalExtension());
            $filename = $this->ASCIIFilter($filename);
            $filename = $this->SpaceTo_($filename);
            $fileNameToStore    = $filename . '.' . $extension;
            // $fileNameToStore    = $filename . '_' . time() . '.' . $extension;
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
            // Storage::disk('local')->makeDirectory('contact_file/'. $contact->id);
            Storage::putFileAs('contact_file/' . $contact->id, $file, $fileNameToStore);

            $file_add = new File([
                'path' => $fileNameToStore,
            ]);
            $contact->files()->save($file_add);

            return $this->succeed([
                "fileName" => $fileNameToStore,
            ], 200);
        }
    }
    public function imageUplode(Request $request)
    {
        $contact_id = (int)$request->contact_id;
        $photo = (string)$request->photo;

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
        $ori_photos = Photo::where('imageable_type', 'App\Contact')->where('imageable_id', $contact_id)->get();
        if ($ori_photos->count() >= 2) {
            return $this->error('檔案存取上限', 101);
        }
        $contact = Contact::find($contact_id);

        $timezone = config('services.time_zone');
        $current = Carbon::now($timezone)->timestamp;
        $storageFile = config('services.storage_file');
        $photo_name = "Contact-" . $contact->id . "-" . $current . ".jpg";
        $photo_path = $storageFile . 'contact_file/' . $photo_name;
        $avatar_small_path = $storageFile . 'contact_file/small/' . $photo_name;
        $avatar_medium_path = $storageFile . 'contact_file/medium/' . $photo_name;
        $avatar_large_path = $storageFile . 'contact_file/large/' . $photo_name;
        //base64 decode
        $extension = explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($photo, 0, strpos($photo, ',') + 1);
        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $photo);
        $image = str_replace(' ', '+', $image);
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        Storage::disk($storage)->makeDirectory($storageFile . 'contact_file/small');
        Storage::disk($storage)->makeDirectory($storageFile . 'contact_file/medium');
        Storage::disk($storage)->makeDirectory($storageFile . 'contact_file/large');
        //
        Storage::disk('local')->put('contact_file/' . $photo_name, base64_decode($image));
        // Storage::move('public/' . $photo_name, 'contact_file/' . $photo_name);
        // return $photo_name;
        $this->compressSmallIMG($photo_path, $avatar_small_path);
        $this->compressMediumIMG($photo_path, $avatar_medium_path);
        $this->compressLargeIMG($photo_path, $avatar_large_path);
        $photo_add = new Photo([
            'path' => $photo_name,
        ]);
        $contact->photos()->save($photo_add);
        // $type = 'photos';
        // $this->updateToContactTable($contact_id, $type);
        return $this->succeed([
            "large_path" => $avatar_large_path,
            "small_path" => $avatar_small_path,
        ], 200);
    }


    //new
    public function new_history(Request $request)
    {
        $user_id = $request->user_id;
        $month = $request->month;
        $contacts = Contact::where('user_id', $user_id)
            ->whereMonth('onboard_date', $month)
            ->get();

        $contacts = $contacts->map(function ($contact) {
            return [
                'id' => $contact->id,
                'onboard_date' => Carbon::parse($contact->onboard_date)->format('Y-m-d'),
                'updated_at' => $contact->updated_at,
                'status' => $contact->status, //0:雙方未讀, 1:家長已讀, 2:老師已讀, 3:雙方已讀,
                'message' => 'old',
            ];
        });
        return $this->succeed($contacts, 200);
    }

    public function new_selectContact(Request $request)
    {
        //0:雙方未讀, 1:家長已讀, 2:老師已讀, 3:雙方已讀,
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;

        $filter = json_decode($request->filter, true);

        $keyword = (string)$filter['keyword'];
        $date = (string)$filter['date'];
        $status = $filter['status'] === null ? null : (int)$filter['status']; //0 or 1

        if ($parent_id) {
            $users = User::whereIn('id', spu_relationship::where('parent_id', $parent_id)->pluck('user_id'))->get();

            if ($status === 0) {
                $info_status = [0, 1];
            } else if ($status === 1) {
                $info_status = [2, 3];
            } else {
                $info_status = [0, 1, 2, 3];
            }
            $contacts = Contact::whereIn('user_id', $users->pluck('id'))->where('name', 'like', "%" . $this->escape_like_str($keyword) . "%")
                ->where(function ($query) use ($date, $status, $info_status) { //符合2者
                    $query->where('onboard_date', $date == null ? '!=' : '=', $date)->whereIn('status', $info_status);
                })
                ->where(function ($query) {
                    $query
                        ->whereNotNull('condition')
                        ->orWhereNotNull('return')->orWhereNotNull('bring')
                        ->orWhereNotNull('to_parent')->orWhereNotNull('to_teacher')
                        ->orWhereNotNull('daily')->orWhereNotNull('mood');
                })
                ->get();
        }

        if ($teacher_id) {
            $users = User::whereIn('department_id', User::find($teacher_id)->departments->pluck('id'))->get();
            if ($status === 0) {
                $info_status = [0, 2];
            } else if ($status === 1) {
                $info_status = [1, 3];
            } else {
                $info_status = [0, 1, 2, 3];
            }
            $contacts = Contact::whereIn('user_id', $users->pluck('id'))->where('name', 'like', "%" . $this->escape_like_str($keyword) . "%")
                ->where(function ($query) use ($date, $status, $info_status) { //符合2者
                    $query->where('onboard_date', $date == null ? '!=' : '=', $date)->whereIn('status', $info_status);
                })
                ->where(function ($query) {
                    $query
                        ->whereNotNull('condition')
                        ->orWhereNotNull('return')->orWhereNotNull('bring')
                        ->orWhereNotNull('to_parent')->orWhereNotNull('to_teacher')
                        ->orWhereNotNull('daily')->orWhereNotNull('mood');
                })
                ->get();
        }
        $contacts = $contacts->map(function ($contact) {
            return [
                'id' => $contact->id,
                'onboard_date' => $contact->onboard_date,
                'updated_at' => $contact->updated_at,
                'status' => $contact->status, //0:雙方未讀, 1:家長已讀, 2:老師已讀, 3:雙方已讀,
                'message' => 'old',
            ];
        });
        return $this->succeed($contacts, 200);
    }

    public function new_updateSync(Request $request)
    {
        $contact_id = (int)$request->id;
        $user_id = (int)$request->user_id;
        $department = User::find($user_id)->department;
        $contact = Contact::find($contact_id); //含新檔案之Contact
        $users = User::where('department_id', $department->id)->select('id')
            ->with(['profile' => function ($Query) {
                $Query->select('user_id', 'name');
            }])
            ->with(['spu_relationship' => function ($Query) {
                $Query->select('user_id', 'parent_id');
            }])
            ->get();
        $user_id_arr = $users->pluck('id');
        //過濾掉已更新檔案的Contact
        $user_id_arr = $user_id_arr->filter(function ($item) use ($user_id) {
            return $item !== $user_id;
        });
        //舊檔案Contacts
        $contacts = Contact::whereIn('user_id', $user_id_arr)->where('onboard_date', $contact->onboard_date)->get();
        //刪除所有舊檔案
        $this->deleteOldFile($contacts);
        $this->deleteOldPhoto($contacts);
        foreach ($users as $user) {
            $user->contacts()
                ->updateOrCreate(
                    [
                        'onboard_date' => $contact->onboard_date,
                    ],
                    [
                        'name' => $user->profile->name,
                        'condition' => $contact->condition,
                        'return' => $contact->return,
                        'bring' => $contact->bring,
                        'daily' => $contact->daily,
                        'school_id' => $department->school_id,
                        'mood' => $contact->mood,
                        'status' => 2, //重置成老師已讀
                    ]
                );
        }
        $contacts = Contact::where('onboard_date', $contact->onboard_date)
            ->whereIn('user_id', $users->pluck('id'))
            ->get();
        //同步第一筆資料
        $this->syncNewFile($contact, $contacts);
        $this->syncNewPhoto($contact, $contacts);
        return $this->succeed('同步完成', 200);
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
    public function new_contactNotify(Request $request)
    {
        $user_id = (int)$request->user_id;
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;
        $contact_id = (int)$request->contact_id;
        $type_message = 'normal';
        $type_sound = 'default';
        $statu = 10;
        $user = User::find($user_id);
        $user_name = $user->profile->name;

        $title = '[聯絡簿]' . $user_name;
        $relation_parent = Parents::find(spu_relationship::where('user_id', $user_id)->first()->parent_id);
        $relation_teacher = User::find($user_id)->department->teacher;

        if ($parent_id) {
            $message = 'from ' . $relation_parent->name;

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
                'type' => 'contact',
                'id' => $contact_id,
                'user_id' => $user_id,
                'user_name' => $user_name,
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
        if ($teacher_id) {
            $message = 'from ' . $relation_teacher->profile->name;

            $notify = new Notify;
            $notify->sent_id = $relation_teacher->id;
            $notify->sent_type = 'App\User';
            $notify->school_id = $relation_teacher->school_id;
            $notify->title = $title;
            $notify->message = $message;
            $notify->target = '家長';
            $notify->statu_id = $statu;
            //對應類型JSON
            $notify->relationship = collect([
                'type' => 'contact',
                'id' => $contact_id,
                'user_id' => $user_id,
                'user_name' => $user_name,
            ])->toJson();

            $token = $relation_parent->device_token;

            //DB to save
            $notify->save();
            $notify->parents()->attach($relation_parent, ['status' => 0, 'student_id' => $user_id]);

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

        return $this->error('parent_id or teacher_id  undefined', 500);
    }

    public function new_contactInfo(Request $request)
    {
        $contact_id = (int)$request->contact_id;
        $contact = Contact::find($contact_id);
        $date_utc = Carbon::parse($contact->onboard_date)->utc();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $relation_parent = Parents::find(spu_relationship::where('user_id', $contact->user_id)->first()->parent_id);
        $relation_teacher = User::find($contact->user_id)->department->teacher;
        if (!($date_utc->gte($yesterday) && $date_utc->lte($today))) {
            //時間超過昨天跟今天只能index
            return $this->succeed([
                'id' => $contact->id,
                'user_id' => $contact->user_id,
                'name' => $contact->user->profile->name,
                'teacher_name' => $relation_teacher->profile->name,
                'parent_name' => $relation_parent->name,
                'temperature' => $contact->temperature_val,
                'onboard_date' => $contact->onboard_date,
                'condition' => $contact->condition,
                'return' => $contact->return,
                'bring' => $contact->bring,
                'daily' => $contact->daily,
                'to_parent' => $contact->to_parent,
                'to_teacher' => $contact->to_teacher,
                'updated_at' => $contact->updated_at,
                'action' => 1, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
            ], 200);
        } else {
            return $this->succeed([
                'id' => $contact->id,
                'user_id' => $contact->user_id,
                'name' => $contact->user->profile->name,
                'teacher_name' => $relation_teacher->profile->name,
                'parent_name' => $relation_parent->name,
                'temperature' => $contact->temperature_val,
                'onboard_date' => $contact->onboard_date,
                'condition' => $contact->condition,
                'return' => $contact->return,
                'bring' => $contact->bring,
                'daily' => $contact->daily,
                'to_parent' => $contact->to_parent,
                'to_teacher' => $contact->to_teacher,
                'updated_at' => $contact->updated_at,
                'action' => 2, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
            ], 200);
        }
    }

    public function new_index(Request $request)
    {
        $user_id = (int)$request->user_id;
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;
        $date = (string)$request->date;
        $date_utc = Carbon::parse($date)->utc();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $student = User::where('id', $user_id)
            ->where('position_id', 10)
            ->where('is_actived', 1)
            ->with('profile:user_id,name')
            ->with(['tempers' => function ($temperQuery) use ($date) {
                $temperQuery->where('temperatures.record_time', 'like', '%' . $date . '%')->orderBy('id', 'desc');
            }])
            ->first();
        $contact = Contact::where('user_id', $user_id)
            ->where('onboard_date', $date)
            ->with('user.profile')
            ->first();


        $relation_parent = Parents::find(spu_relationship::where('user_id', $user_id)->first()->parent_id);
        $relation_teacher = User::find($user_id)->department->teacher;

        if (!($date_utc->gte($yesterday) && $date_utc->lte($today))) {
            //時間超過昨天跟今天只能index
            if ($contact == null) {
                return $this->succeed([
                    'name' => $student->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'message' => 'no',
                    'action' => 1, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                ], 200);
            } else {
                if ($parent_id) {
                    $contact = Contact::where('user_id', $user_id)
                        ->where('onboard_date', $date)
                        ->with('user.profile')
                        ->where(function ($query) {
                            $query
                                ->where(function ($conditionQuery) {
                                    $conditionQuery
                                        ->whereNotNull('condition')
                                        ->where('condition', '!=', "[]");
                                })
                                ->orWhere(function ($returnQuery) {
                                    $returnQuery
                                        ->whereNotNull('return')
                                        ->where('return', '!=', "[]");
                                })
                                ->orWhere(function ($bringQuery) {
                                    $bringQuery
                                        ->whereNotNull('bring')
                                        ->where('bring', '!=', "[]");
                                })
                                ->orWhereNotNull('to_parent')->orWhereNotNull('to_teacher')
                                ->orWhereNotNull('daily')->orWhereNotNull('mood');
                        })
                        ->first();
                    // return $contact;
                    if ($contact === null) { //欄位全空缺當作沒有
                        return $this->succeed([
                            'name' => $student->profile->name,
                            'teacher_name' => $relation_teacher->profile->name,
                            'parent_name' => $relation_parent->name,
                            'message' => 'no',
                            'action' => 1, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                        ], 200);
                    }
                }
                if ($parent_id  && $contact->status == 2) {
                    Contact::where('id', $contact->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                //老師自己看不會重置
                if ($teacher_id  && $contact->status == 1) {
                    Contact::where('id', $contact->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                $attachment = $this->new_getContactAttachment($contact->id);
                return $this->succeed(array_merge([
                    'id' => $contact->id,
                    'name' => $contact->user->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'temperature' => $contact->temperature,
                    'condition' => $contact->condition,
                    'return' => $contact->return,
                    'bring' => $contact->bring,
                    'daily' => $contact->daily,
                    'to_parent' => $contact->to_parent,
                    'to_teacher' => $contact->to_teacher,
                    'mood' => $contact->mood,
                    'updated_at' => $contact->updated_at,
                    'message' => 'old',
                    'action' => 1, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建,
                ], $attachment), 200);
            }
        } else {
            if ($contact == null) {
                if ($parent_id) { //家長只能看
                    return $this->succeed([
                        'name' => $student->profile->name,
                        'teacher_name' => $relation_teacher->profile->name,
                        'parent_name' => $relation_parent->name,
                        'message' => 'no',
                        'action' => 4, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                    ], 200);
                }
                if ($teacher_id) {
                    //get student temper & parent
                    $item = collect([
                        'user_id' => $student->id,
                        'user_name' => $student->profile->name,
                        'temperature' => $student->tempers->first()['temperature_val'],
                        // 'parent_id' => $relation_parent->parent_id,
                        // 'teacher_id' => $relation_teacher->id,
                        'school_id' => $student->school_id,
                        'onboard_date' => $date,
                    ]);
                    //抓到null，新增一筆空資料
                    //new contact
                    $contact = new Contact;
                    $contact->user_id = $item->get('user_id');
                    $contact->name = $item->get('user_name');
                    $contact->temperature = $item->get('temperature');
                    // $contact->parent_id = $item->get('parent_id');
                    // $contact->teacher_id = $item->get('teacher_id');
                    $contact->school_id = $item->get('school_id');
                    $contact->onboard_date = $item->get('onboard_date');
                    $contact->status = 0; //已讀重置
                    $contact->save();
                    $attachment = $this->new_getContactAttachment($contact->id);
                    return $this->succeed(array_merge([
                        'id' => $contact->id,
                        'temperature' => $contact->temperature,
                        'name' => $student->profile->name,
                        'teacher_name' => $relation_teacher->profile->name,
                        'parent_name' => $relation_parent->name,
                        'condition' => $contact->condition,
                        'return' => $contact->return,
                        'bring' => $contact->bring,
                        'daily' => $contact->daily,
                        'to_parent' => $contact->to_parent,
                        'to_teacher' => $contact->to_teacher,
                        'mood' => $contact->mood,
                        'message' => 'new',
                        'action' => 2, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                    ], $attachment), 200);
                }
            } else { //已經有新增過


                if ($parent_id  && $contact->status == 2) {
                    Contact::where('id', $contact->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }
                //老師自己看不會重置
                if ($teacher_id  && $contact->status == 1) {
                    Contact::where('id', $contact->id)
                        ->update([
                            'status' => 3, //重置成雙方已讀
                        ]);
                }

                if ($parent_id) {
                    $contact = Contact::where('user_id', $user_id)
                        ->where('onboard_date', $date)
                        ->with('user.profile')
                        ->where(function ($query) {
                            $query
                                ->where(function ($conditionQuery) {
                                    $conditionQuery
                                        ->whereNotNull('condition')
                                        ->where('condition', '!=', "[]");
                                })
                                ->orWhere(function ($returnQuery) {
                                    $returnQuery
                                        ->whereNotNull('return')
                                        ->where('return', '!=', "[]");
                                })
                                ->orWhere(function ($bringQuery) {
                                    $bringQuery
                                        ->whereNotNull('bring')
                                        ->where('bring', '!=', "[]");
                                })
                                ->orWhereNotNull('to_parent')->orWhereNotNull('to_teacher')
                                ->orWhereNotNull('daily')->orWhereNotNull('mood');
                        })
                        ->first();
                    // return $contact;
                    if ($contact === null) { //欄位全空缺當作沒有
                        return $this->succeed([
                            'name' => $student->profile->name,
                            'teacher_name' => $relation_teacher->profile->name,
                            'parent_name' => $relation_parent->name,
                            'message' => 'no',
                            'action' => 4, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                        ], 200);
                    }
                    //家長限制時間內不能編輯
                    $range = Setting::where('school_id', $contact->school_id)->first();
                    $start = $range->contact_start_at;
                    $end   = $range->contact_finish_at;
                    $now   = Carbon::now()->format('H:i:s');
                    // $now = "19:00:00";
                    // return $now < $start || $now > $end ? 1 : 2;
                    if ($now > $start && $now < $end) {
                        $attachment = $this->new_getContactAttachment($contact->id);
                        return $this->succeed(array_merge([
                            'id' => $contact->id,
                            'name' => $contact->user->profile->name,
                            'teacher_name' => $relation_teacher->profile->name,
                            'parent_name' => $relation_parent->name,
                            'temperature' => $student->tempers->first()['temperature_val'],
                            'condition' => $contact->condition,
                            'return' => $contact->return,
                            'bring' => $contact->bring,
                            'daily' => $contact->daily,
                            'to_parent' => $contact->to_parent,
                            'to_teacher' => $contact->to_teacher,
                            'mood' => $contact->mood,
                            'updated_at' => $contact->updated_at,
                            'message' => 'old',
                            'action' => 3, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                        ], $attachment), 200);
                    }
                }
                $attachment = $this->new_getContactAttachment($contact->id);
                return $this->succeed(array_merge([
                    'id' => $contact->id,
                    'name' => $contact->user->profile->name,
                    'teacher_name' => $relation_teacher->profile->name,
                    'parent_name' => $relation_parent->name,
                    'temperature' => $student->tempers->first()['temperature_val'],
                    'condition' => $contact->condition,
                    'return' => $contact->return,
                    'bring' => $contact->bring,
                    'daily' => $contact->daily,
                    // 'section_id' => $contact->sections->get('id'),
                    // 'section_name' => $contact->sections->get('name'),
                    'to_parent' => $contact->to_parent,
                    'to_teacher' => $contact->to_teacher,
                    'mood' => $contact->mood,
                    'updated_at' => $contact->updated_at,
                    'message' => 'old',
                    'action' => 2, // 1:今天跟昨天之外, 2:edit, 3:家長編輯時間外, 4:老師尚未創建
                    // 'onboard_date' => $contact->onboard_date,
                ], $attachment), 200);
            }
        }
    }
    public function new_getContactAttachment($contact_id)
    {
        $contact = Contact::find($contact_id);
        $photos = $contact->photos;
        $files = $contact->files;
        $photos = $photos->map(function ($photo) {
            $collection = collect([
                'id' => $photo->photo_id,
                'path' => 'contact_file/small/' . $photo->path,
            ]);
            return $collection;
        });
        $files = $files->map(function ($file) {
            $collection = collect([
                'id' => $file->file_id,
                'path' => 'contact_file/' . $file->imageable_id . '/' . $file->path,
            ]);
            return $collection;
        });

        return [
            'not_images' => $files,
            'images' => $photos
        ];
    }
    public function new_updateNew(Request $request)
    {
        $contact_id = (int)$request->id;
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;
        $condition = $request->condition;
        $return = $request->return;
        $bring = $request->bring;
        $to_parent = $request->to_parent;
        $to_teacher = $request->to_teacher;
        $daily = $request->daily;
        $mood = $request->mood;

        if ($parent_id) {
            $contact =  Contact::where('id', $contact_id)->first();
            $contact->update([
                'to_teacher' => $to_teacher,
                'status' => 1, //重置成家長已讀
            ]);
            $this->returnContact($contact->user_id);
            return $this->succeed('更新完成', 200);
        }
        if ($teacher_id) {
            $file_edit = false;
            $photo_edit = false;
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
            if ($request->hasFile('file')) {
                if (count($request->file('file')) > 2) {
                    return $this->succeed('檔案存取上限', 501);
                }
                foreach ($request->file('file') as $file) {
                    $validator = Validator::make(
                        [
                            'file' => $file,
                        ],
                        [
                            'file' => 'required|mimes:pdf,docx,doc,pptx,ppt,xlsx,xls',
                        ]
                    );
                    if ($validator->fails()) {
                        return $this->errors('檔案格式不符(pdf,doc,xls,ppt)', $validator->errors(), 401);
                    }
                }
                $file_edit = true;
            }
            if ($request->has('photo') && $photo_edit) {
                //確認完全符合數量及格式規則後，刪除所有舊檔案再新增
                $ori_photos = Photo::where('imageable_type', 'App\Contact')->where('imageable_id', $contact_id)->get();
                foreach ($ori_photos as $ori_photo) {
                    $this->new_deleteImage($ori_photo);
                }
                foreach ($photo_arr as $photo) {
                    $this->new_imageUplode($contact_id, $photo);
                    sleep(1);
                }
            }
            if ($request->hasFile('file') && $file_edit) {
                //確認完全符合數量及格式規則後，刪除所有舊檔案再新增
                $ori_files = File::where('imageable_type', 'App\Contact')->where('imageable_id', $contact_id)->get();
                foreach ($ori_files as $ori_file) {
                    $this->new_deleteNotImage($ori_file);
                }
                foreach ($request->file('file') as $file) {
                    $this->new_notImageUplode($contact_id, $file);
                }
            }
            Contact::where('id', $contact_id)
                ->update([
                    'condition' => $condition,
                    'return' => $return,
                    'bring' => $bring,
                    'to_parent' => $to_parent,
                    'daily' => $daily,
                    'mood' => $mood,
                    'status' => 2, //重置成老師已讀
                ]);
            return $this->succeed('更新完成', 200);
        }

        return $this->error('parent_id or teacher_id undefined', 250);
    }
    public function new_notImageUplode($contact_id, $file)
    {
        $contact = Contact::find($contact_id);
        $filenameWithExt    = $file->getClientOriginalName();
        $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension          = strtolower($file->getClientOriginalExtension());
        $filename = $this->ASCIIFilter($filename);
        $filename = $this->SpaceTo_($filename);
        $fileNameToStore    = $filename . '.' . $extension;
        // $fileNameToStore    = $filename . '_' . time() . '.' . $extension;
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
        // Storage::disk('local')->makeDirectory('contact_file/'. $contact->id);
        Storage::putFileAs('contact_file/' . $contact->id, $file, $fileNameToStore);

        $file_add = new File([
            'path' => $fileNameToStore,
        ]);
        $contact->files()->save($file_add);
    }
    public function new_deleteNotImage($ori_file)
    {
        $storage = config('services.storage');
        Storage::disk($storage)->delete('/contact_file/' . $ori_file->imageable_id . '/' . $ori_file->path);
        $ori_file->delete();
    }
    public function new_imageUplode($contact_id, $photo)
    {
        $contact = Contact::find($contact_id);

        $timezone = config('services.time_zone');
        $current = Carbon::now($timezone)->timestamp;
        $storageFile = config('services.storage_file');
        $photo_name = "Contact-" . $contact->id . "-" . $current . ".jpg";
        $photo_path = $storageFile . 'contact_file/' . $photo_name;
        $avatar_small_path = $storageFile . 'contact_file/small/' . $photo_name;
        $avatar_medium_path = $storageFile . 'contact_file/medium/' . $photo_name;
        $avatar_large_path = $storageFile . 'contact_file/large/' . $photo_name;
        //base64 decode
        //$extension = explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($photo, 0, strpos($photo, ',') + 1);
        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $photo);
        $image = str_replace(' ', '+', $image);
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        Storage::disk($storage)->makeDirectory($storageFile . 'contact_file/small');
        Storage::disk($storage)->makeDirectory($storageFile . 'contact_file/medium');
        Storage::disk($storage)->makeDirectory($storageFile . 'contact_file/large');
        //
        Storage::disk('local')->put('contact_file/' . $photo_name, base64_decode($image));
        // Storage::move('public/' . $photo_name, 'contact_file/' . $photo_name);
        // return $photo_name;
        $this->compressSmallIMG($photo_path, $avatar_small_path);
        $this->compressMediumIMG($photo_path, $avatar_medium_path);
        $this->compressLargeIMG($photo_path, $avatar_large_path);
        $photo_add = new Photo([
            'path' => $photo_name,
        ]);
        $contact->photos()->save($photo_add);
    }
    public function new_deleteImage($ori_photo)
    {
        $photos = Photo::where('path', $ori_photo->path)->get(); //共用同一照片的
        $storage = config('services.storage');
        if ($photos->count() === 1) {
            Storage::disk($storage)->delete('/contact_file/' . $ori_photo->path);
            Storage::disk($storage)->delete('/contact_file/small/' . $ori_photo->path);
            Storage::disk($storage)->delete('/contact_file/medium/' . $ori_photo->path);
            Storage::disk($storage)->delete('/contact_file/large/' . $ori_photo->path);
        }
        $ori_photo->delete();
    }
}
