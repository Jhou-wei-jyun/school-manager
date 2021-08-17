<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Record;
use App\User;
use App\Admin;
use App\Avatar;
use App\School;
use App\sdd_relationship;
use App\Department;
use App\Position;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use App\API\ApiHelper;
use App\Chat_message;
use App\Depart;

class DepartmentController extends Controller
{
    use ApiHelper;

    public function SelectDepartment(Request $request)
    {
        $department_id = $request->department_id;
        $school_id = $request->school_id;
        if ($department_id) {
            $depart = School::find($school_id)->departments()->where('id', $department_id)->get();
            return $depart;
        } else {
            $all = School::find($school_id)->departments;
            return $all;
        }
    }
    public function index(Request $request)
    {
        $school_id = $request->school_id;
        $lists = sdd_relationship::where('school_id', $school_id)
            ->with('depart', 'department')->get();
        // return $lists;
        $lists = $lists->map(function ($list) {
            $collection =  collect([
                'id' => $list->department->id,
                'name' => $list->department->name,
                'avatar' => $list->department->avatar,
                'supervisor_id' => $list->department->supervisor_id,
                'depart_id' => $list->depart->depart_id,
                'depart_name' => $list->depart->name,
            ]);
            return $collection;
        })->groupBy('depart_name');

        return $lists;
    }
    public function detail(Request $request)
    {
        $id = $request->department_id;
        $department = Department::find($id);

        $department_detail =  collect([
            'depart_id' => $department->id,
            'depart_name' => $department->name,
            'teacher_id' => $department->teacher->id,
            'teacher_name' => $department->teacher->profile->name,
            'start' => $department->start_at,
            'end' => $department->finish_at,
            'count' => $department->students()->count(),
        ]);

        return $department_detail;
    }
    public function SelectTeacher(Request $request)
    {
        $school_id = $request->school_id;
        $teachers = School::find($school_id)->users()
            ->whereNotIn('position_id', [10, 20])
            ->where('is_actived', true)
            ->with('profile')
            ->get();
        $teachers = $teachers->map(function ($teacher) {

            $collection =  collect([
                'id' => $teacher->id,
                'name' => $teacher->profile->name,
            ]);

            return $collection;
        });

        return $teachers;
    }
    public function SelectDepart(Request $request)
    {
        $school_id = $request->school_id;
        $departs = School::find($school_id)->departs()->get();
        $departs = $departs->map(function ($depart) {

            $collection =  collect([
                'id' => $depart->depart_id,
                'name' => $depart->name,
            ]);

            return $collection;
        });

        return $departs;
    }
    public function addDepart(Request $request)
    {
        $school_id = $request->school_id;
        $name = $request->name;

        $add_depart = new Depart([
            'name' => $name,
            'school_id' => $school_id,
        ]);
        $add_depart->save();
        return $this->succeed($add_depart, 200);
    }
    public function store(Request $request)
    {
        $name = $request->name;
        $admin_id = (int) $request->admin_id;
        $depart_id = $request->selectDepart;
        $supervisor_id = $request->selectteacher;
        $start_at = $request->startTime;
        $finish_at = $request->finishTime;
        $school_id = $request->school_id;
        //驗證
        $validator = Validator::make(
            [
                'name' => $name,
                'supervisor_id' => $supervisor_id,
                'start_at' => $start_at,
                'finish_at' => $finish_at,
                'school_id' => $school_id,
                'depart_id' => $depart_id,
            ],
            [
                'name' => 'required',
                'supervisor_id' => 'required',
                'start_at' => 'required',
                'finish_at' => 'required',
                'school_id' => 'required',
                'depart_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('fails', $validator->errors(), 401);
        }
        $school = School::find($school_id);
        $department_add = new Department([
            'name' => $name,
            'supervisor_id' => $supervisor_id,
            'start_at' => $start_at,
            'finish_at' => $finish_at,
        ]);
        $school->departments()->save($department_add);
        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($department_add)
                ->withProperties(['type' => 'store'])
                ->log('新增班級');
        }
        //relationship save
        $sdd_relationship = new sdd_relationship([
            'school_id' => $school_id,
            'depart_id' => $depart_id,
            'department_id' => $department_add->id,
        ]);
        $sdd_relationship->save();

        return $this->succeed('', 200);
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $admin_id = (int) $request->admin_id;
        $name = $request->name;
        $startTime = $request->startTime;
        $finishTime = $request->finishTime;
        $supervisor_id = $request->selectteacher;
        $department = Department::find($id);
        $students_list = $department->students->pluck('id'); //取得底下學生id

        $department->update(
            [
                'name' => $name,
                'start_at' => $startTime,
                'finish_at' => $finishTime,
                'supervisor_id' => $supervisor_id,
            ]
        );
        foreach ($students_list as $student) {
            $recode_notifies = User::where('school_id', $department->school_id) //新增的id以前的推播紀錄
                ->with(['notifies' => function ($query) use ($student) {
                    $query->where('student_id', $student);
                }])
                ->get()->pluck('notifies')->collapse()->unique('id');
            foreach ($recode_notifies as $recode_notify) {
                $recode_notify->users()->sync([$supervisor_id => ['status' => 0, 'student_id' => $student]]);
            }
            // Chat_message::where('user_id', $student) //新增的id以前的聊天紀錄
            //     ->update([
            //         'teacher_id' => $supervisor_id
            //     ]);
        }

        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($department)
                ->withProperties(['type' => 'edit'])
                ->log('編輯班級');
        }

        return $this->succeed('', 200);
    }
    public function delete(Request $request)
    {
        $id = $request->department_id;
        $admin_id = (int) $request->admin_id;
        $department = Department::find($id);
        $department->areas()->detach();
        $department->sdd_relationship()->delete();
        $department->delete();
        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($department)
                ->withProperties(['type' => 'delete'])
                ->log('刪除班級');
        }

        $department->students()
            ->update(['department_id' => null]);
        return $this->succeed('', 200);
    }
    public function avatar_index(Request $request)
    {
        $school_id = $request->school_id;
        $avatars = School::find($school_id)->avatars;
        $avatars = $avatars->map(function ($avatar) {

            $collection =  collect([
                'id' => $avatar->id,
                'avatar' => $avatar->avatar,
            ]);

            return $collection;
        });
        return $avatars;
    }
    public function avatar_store(Request $request)
    {
        $school_id = $request->school_id;
        $admin_id = (int) $request->admin_id;
        $avatar_file = $request->file('avatar_file');
        //驗證
        $validator = Validator::make(
            [
                'avatar_file' => $avatar_file,
            ],
            [
                'avatar_file' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('檔案未上傳', $validator->errors(), 401);
        }

        $school = School::find($school_id);
        $avatar_add = new Avatar([
            'avatar' => 'wait',
        ]);
        $school->avatars()->save($avatar_add);
        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($avatar_add)
                ->withProperties(['type' => 'store'])
                ->log('新增班級圖像');
        }
        $storageFile = config('services.storage_file');
        $storage = config('services.storage');
        $avatar_name = "Icon_" . $avatar_add->id . ".jpg";
        $avatar_path = $storageFile . 'department_icon/' . $avatar_name;
        Storage::disk($storage)->putFileAs($storageFile . 'department_icon', $avatar_file, $avatar_name);

        Avatar::find($avatar_add->id)
            ->update([
                'avatar' => $avatar_path,
            ]);

        return $this->succeed('', 200);
    }
    public function avatar_change(Request $request)
    {
        $id = $request->department_id;
        $admin_id = (int) $request->admin_id;
        $avatar = $request->avatar;

        $department =  Department::find($id);
        $department->update(
            [
                'avatar' => $avatar,
            ]
        );
        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($department)
                ->withProperties(['type' => 'edit'])
                ->log('編輯班級圖像');
        }

        return $this->succeed('', 200);
    }
    public function avatar_delete(Request $request)
    {
        $id = $request->avatar_id;
        $admin_id = (int) $request->admin_id;
        $item = Avatar::find($id);
        $storage = config('services.storage');
        Storage::disk($storage)->delete('/' . $item->avatar);

        $item->delete();
        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($item)
                ->withProperties(['type' => 'delete'])
                ->log('刪除班級圖像');
        }
        return $this->succeed('', 200);
    }
}
