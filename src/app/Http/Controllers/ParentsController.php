<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin;
use App\User;
use App\School;
use App\Parents;
use App\spu_relationship;
use App\Notify;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\API\ApiHelper;
use App\Chat_message;
use Illuminate\Support\Str;

class ParentsController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {

        $school_id = $request->school_id;
        $parents = School::find($school_id)->parents()
            ->with('spu_relationship.user.profile')
            ->get();

        $parents = $parents->map(function ($parent) {

            $collection =  collect([
                'parent_id' => $parent->parent_id,
                'name' => $parent->name,
                'phone' => $parent->phone,
                'title' => $parent->title,
                'student_id' => $parent->spu_relationship->pluck('user_id'),
                'student_name' => $parent->spu_relationship->pluck('user.profile.name'),
            ]);

            return $collection;
        });

        return $parents;
    }
    public function store(Request $request)
    {
        $name = $request->name;
        $admin_id = (int) $request->admin_id;
        $title = $request->title;
        $phone = $request->phone;
        $school_id = $request->school_id;
        //驗證
        $validator = Validator::make(
            [
                'phone' => $phone,
            ],
            [
                'phone' => 'required|unique:users|unique:parents',
            ]
        );
        if ($validator->fails()) {
            return $this->errors('電話號碼重複', $validator->errors(), 401);
        }
        $school = School::find($school_id);
        $parent_add = new Parents([
            'name' => $name,
            'title' => $title,
            'phone' => $phone,
        ]);
        $school->parents()->save($parent_add);
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($parent_add)
                ->withProperties([
                    'type' => 'store',
                    'result' => 'success',
                ])
                ->log('新增家長');
        }
        return $this->succeed('', 200);
    }
    public function update(Request $request)
    {
        $parent_id = $request->parent_id;
        $admin_id = (int) $request->admin_id;
        $name = $request->name;
        $title = $request->title;
        $phone = $request->phone;
        $parent = Parents::find($parent_id);
        //驗證 Phone 有無更動
        if ($parent->phone != $phone) {
            //有，驗證其他有無重複
            $validator = Validator::make(
                [
                    'phone' => $phone,
                ],
                [
                    'phone' => 'required|unique:users|unique:parents',
                ]
            );
            if ($validator->fails()) {
                return $this->errors('電話重複', $validator->errors(), 401);
            }
        }
        $parent->update(
            [
                'name' => $name,
                'title' => $title,
                'phone' => $phone,
            ]
        );
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($parent)
                ->withProperties([
                    'type' => 'edit',
                    'result' => 'success',
                ])
                ->log('編輯家長');
        }
        return $this->succeed('', 200);
    }
    public function delete(Request $request)
    {
        $parent_id = $request->parent_id;
        $admin_id = (int) $request->admin_id;
        $parent = Parents::find($parent_id);

        $parent->spu_relationship()->delete();
        $parent->delete();
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->performedOn($parent)
                ->withProperties([
                    'type' => 'delete',
                    'result' => 'success',
                ])
                ->log('刪除家長');
        }
        return $this->succeed('', 200);
    }
    public function parent_relationship_change(Request $request)
    {
        // $validator = Validator::make($request->all(), Parents::$registerDepartmentRule);

        // if ($validator->fails()) {
        //     return $validator->errors();
        // }
        $admin_id = (int) $request->admin_id;
        $parent_id = $request->parent_id;
        $school_id = $request->school_id;
        $user_ids = $request->user_id;
        $user_id_list = [];
        if ($user_ids !== null) {
            $pieces = explode(",", $user_ids);
            for ($i = 0; $i < count($pieces); $i++) {
                $user_id_list[] = (int) $pieces[$i];
            }
        }
        $spu_del = spu_relationship::where('parent_id', '=', $parent_id);
        $ori_user_id_list = $spu_del->pluck('user_id')->toArray();

        $user_id_diff = array_diff($user_id_list, $ori_user_id_list); //新增的id
        // return $user_id_diff;

        $spu_del->delete();
        $pieces = [];
        foreach ($user_id_list as $user_id) {
            $spu_item = new spu_relationship;
            $spu_item->parent_id = $parent_id;
            $spu_item->school_id = $school_id;
            $spu_item->user_id = $user_id;
            $spu_item->save();
            foreach ($user_id_diff as $id_diff) {
                if (in_array($user_id, $user_id_diff)) {
                    $recode_notifies = Parents::where('school_id', $school_id) //新增的id以前的推播紀錄
                        ->with(['notifies' => function ($query) use ($id_diff) {
                            $query->where('student_id', $id_diff);
                        }])
                        ->get()->pluck('notifies')->collapse()->unique('id');
                    foreach ($recode_notifies as $recode_notify) {
                        $recode_notify->parents()->sync([$parent_id => ['status' => 0, 'student_id' => $id_diff]]);
                    }
                    // Chat_message::where('user_id', $user_id) //新增的id以前的聊天紀錄
                    //     ->update([
                    //         'parent_id' => $parent_id
                    //     ]);
                }
            }
        }
        //log
        if ($admin_id) {
            activity()
                ->causedBy(Admin::find($admin_id))
                ->withProperties([
                    'type' => 'edit',
                    'result' => 'success',
                    'target' => $pieces,
                ])
                ->log('編輯家長學生關聯');
        }
        return $this->succeed('', 200);
    }
    public function parent_all_student_list(Request $request)
    {
        $parent_id = $request->parent_id;
        $school_id = $request->school_id;

        // 小朋友已配對的
        $paired = School::find($school_id)->parents()->find($parent_id)
            ->spu_relationship()->with('user.profile')->get();
        $paired = $paired->map(function ($pair) {

            $collection =  collect([
                'id' => $pair->user->id,
                'name' => $pair->user->profile->name,
            ]);

            return $collection;
        });

        // 還沒配對的小孩
        $query = spu_relationship::join('users', 'users.id', '=', 'user_id')
            ->select('users.id')->get();

        $notpaired = School::find($school_id)->users()
            ->where('position_id', '=', 10)
            ->where('is_actived', '=', 1)
            ->whereNotIn('id', $query)
            ->get();
        $notpaired = $notpaired->map(function ($notpair) {

            $collection =  collect([
                'id' => $notpair->id,
                'name' => $notpair->profile->name,
            ]);

            return $collection;
        });
        $all_student = array_sort_recursive(array_collapse([$notpaired, $paired]));

        return $all_student;
    }
    public function parent_pair_student_list(Request $request)
    {
        $parent_id = $request->parent_id;
        $school_id = $request->school_id;

        $paired = School::find($school_id)->parents()->find($parent_id)
            ->spu_relationship()->with('user.profile')->get();


        $paired = $paired->map(function ($pair) {

            $collection =  collect([
                'id' => $pair->user->id,
                'name' => $pair->user->profile->name,
            ]);

            return $collection;
        });

        return $paired;
        // 已經配對的
        // $paired = spu_relationship::join('users', 'users.id', '=','user_id')
        //             ->where('position_id', '=', 10)
        //             ->where('is_actived', '=', 1)
        //             ->where('spu_relationships.school_id', '=', $school_id)
        //             ->where('parent_id', '=', $parent_id)
        //             ->select(['users.id','name','gender'])
        //             ->get();

        // return $paired;

    }
}
