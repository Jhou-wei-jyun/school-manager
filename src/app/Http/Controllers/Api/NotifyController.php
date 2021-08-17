<?php

namespace App\Http\Controllers\Api;

use App\API\ApiHelper;
use Carbon\Carbon;
use App\User;
use App\Parents;
use App\Notify;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\GetList;
use App\spu_relationship;

class NotifyController extends Controller
{
    use ApiHelper;
    public function status_change($teacher_id, $parent_id, $notify)
    {
        if ($teacher_id) {
            $notify->users()->updateExistingPivot($teacher_id, ['status' => 1], false);
        }
        if ($parent_id) {
            $notify->parents()->updateExistingPivot($parent_id, ['status' => 1], false);
        }
    }

    public function index(Request $request)
    {
        $teacher_id = $request->teacher_id;
        $parent_id = $request->parent_id;
        $timezone = config('services.time_zone');
        $range = Carbon::now($timezone)->subDays(7)->startOfDay();


        // dd($range);
        if ($teacher_id) {
            $notifies = User::find($teacher_id)
                ->notifies()
                ->where('notifies.created_at', '>=', $range)
                ->orderBy('id', 'desc')
                ->get();
        }
        if ($parent_id) {
            $notifies = Parents::find($parent_id)
                ->notifies()
                ->where('notifies.created_at', '>=', $range)
                ->orderBy('id', 'desc')
                ->get();
        }

        $notifies = $notifies->map(function ($notify) {
            $collection = collect([
                'id' => $notify->id,
                'title' => $notify->title,
                'message' => $notify->message,
                'time' => Carbon::parse($notify->created_at)->format('Y-m-d H:i:s'),
                'status' => $notify->pivot->status,
                'type' => $notify->statu_id, //10:normal, 11:emergency
                'relationship' => $notify->relationship != null ? json_decode($notify->relationship) : null
            ]);
            return $collection;
        });
        return $notifies;
    }
    public function info(Request $request)
    {
        //Notify id
        $teacher_id = $request->teacher_id;
        $parent_id = $request->parent_id;
        $notify_id = $request->notify_id;
        // $notifies = Notify::where('id',$request->id)->get();
        $notify = Notify::find($notify_id);
        $this->status_change($teacher_id, $parent_id, $notify);
        // $notifies = $notifies->map(function ($notify) {
        //     $collection = collect([
        //         'image' => json_decode($notify->image,true),
        //     ]);
        //     return $collection;
        // });
        return $notify;
        // if ($notify->image == null){
        //     return null;
        // }else {
        //     return $notify->image;
        // }
        // return json_decode($notify->image,true);
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
    public function OriginSelectNotify(Request $request)
    {
        //0:未讀, 1:已讀
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;
        $filter = json_decode($request->filter, true);

        $keyword = (string)$filter['keyword'];
        $date = (string)$filter['date'];
        $status = $filter['status'] === null ? null : (int)$filter['status']; //0 or 1
        if ($status === 0) {
            $info_status = [0];
        } else if ($status === 1) {
            $info_status = [1];
        } else {
            $info_status = [0, 1];
        }
        if ($teacher_id) {
            $department_id_list = User::find($teacher_id)->departments()->pluck('id');
            $students_id_list = User::whereIn('department_id', $department_id_list)->pluck('id');

            $notifies = User::find($teacher_id)
                ->notifies()
                ->where('title', 'like', "%" . $this->escape_like_str($keyword) . "%")
                ->whereDate('notifies.created_at', $date == null ? '!=' : '=', $date)
                ->whereIn('status', $info_status)
                ->where(function ($query) use ($students_id_list) {
                    $query->where('student_id', null)
                        ->orWhereIn('student_id', $students_id_list);
                })
                ->orderBy('id', 'desc')
                ->get();
        }
        if ($parent_id) {
            $students_id_list = spu_relationship::where('parent_id', $parent_id)->pluck('user_id');

            $notifies = Parents::find($parent_id)
                ->notifies()
                ->where('title', 'like', "%" . $this->escape_like_str($keyword) . "%")
                ->whereDate('notifies.created_at', $date == null ? '!=' : '=', $date)
                ->whereIn('status', $info_status)
                ->where(function ($query) use ($students_id_list) {
                    $query->where('student_id', null)
                        ->orWhereIn('student_id', $students_id_list);
                })
                ->orderBy('id', 'desc')
                ->get();
        }

        $notifies = $notifies->map(function ($notify) {
            $collection = collect([
                'id' => $notify->id,
                'title' => $notify->title,
                'message' => $notify->message,
                'time' => Carbon::parse($notify->created_at)->format('Y-m-d H:i:s'),
                'status' => $notify->pivot->status,
                'type' => $notify->statu_id, //10:normal, 11:emergency
                'relationship' => $notify->relationship != null ? json_decode($notify->relationship) : null
            ]);
            return $collection;
        });
        return $notifies;
    }
    public function selectNotify(Request $request)
    {
        //0:未讀, 1:已讀
        $parent_id = (int)$request->parent_id;
        $teacher_id = (int)$request->teacher_id;
        $filter = json_decode($request->filter, true);
        $current_sort = (int)$request->current_sort;
        $start = (int)$request->start;
        if ($start === 0) {
            $start = 1;
        }
        $limit = (int)$request->limit;
        if ($start > $limit) {
            return $this->error('不能為負數', 507);
        }
        $keyword = (string)$filter['keyword'];
        $date = (string)$filter['date'];
        $status = $filter['status'] === null ? null : (int)$filter['status']; //0 or 1
        if ($status === 0) {
            $info_status = [0];
        } else if ($status === 1) {
            $info_status = [1];
        } else {
            $info_status = [0, 1];
        }
        if ($teacher_id) {
            $department_id_list = User::find($teacher_id)->departments()->pluck('id');
            $students_id_list = User::whereIn('department_id', $department_id_list)->pluck('id');

            $notifies = User::find($teacher_id)
                ->notifies()
                ->where('title', 'like', "%" . $this->escape_like_str($keyword) . "%")
                ->whereDate('notifies.created_at', $date == null ? '!=' : '=', $date)
                ->whereIn('status', $info_status)
                ->where(function ($query) use ($students_id_list) {
                    $query->where('student_id', null)
                        ->orWhereIn('student_id', $students_id_list);
                })
                ->orderBy('id', $current_sort === 0 ? 'asc' : 'desc')
                ->skip($start - 1)->take($limit - $start + 1)->get();
        }
        if ($parent_id) {
            $students_id_list = spu_relationship::where('parent_id', $parent_id)->pluck('user_id');

            $notifies = Parents::find($parent_id)
                ->notifies()
                ->where('title', 'like', "%" . $this->escape_like_str($keyword) . "%")
                ->whereDate('notifies.created_at', $date == null ? '!=' : '=', $date)
                ->whereIn('status', $info_status)
                ->where(function ($query) use ($students_id_list) {
                    $query->where('student_id', null)
                        ->orWhereIn('student_id', $students_id_list);
                })
                ->orderBy('id', $current_sort === 0 ? 'asc' : 'desc')
                ->skip($start - 1)->take($limit - $start + 1)->get();
        }

        $notifies = $notifies->map(function ($notify) {
            $collection = collect([
                'id' => $notify->id,
                'title' => $notify->title,
                'message' => $notify->message,
                'time' => Carbon::parse($notify->created_at)->format('Y-m-d H:i:s'),
                'status' => $notify->pivot->status,
                'type' => $notify->statu_id, //10:normal, 11:emergency
                'relationship' => $notify->relationship != null ? json_decode($notify->relationship) : null
            ]);
            return $collection;
        });
        return $notifies;
    }
    // public function index(Request $request)
    // {
    //     $user = $request->user();
    //     // $user_notifies = new GetList([
    //     //     'query'=> $user->notifies(),
    //     //     'timestamp'=> $request->timestamp,
    //     //     'type' => 'user_notifies',
    //     // ]);
    //     $timestamp = $request->timestamp;
    //     if ($timestamp == 0){

    //         $list =  $user->notifies()->orderBy('updated_at', 'asc')->get();
    //     }
    //     if ($timestamp != 0){
    //         $updated_at = date('Y-m-d H:i:s',$timestamp);
    //         $list = $user->notifies()->where('notifies.updated_at','>',$updated_at)
    //             ->orderBy('updated_at', 'asc')
    //             ->get();
    //     }

    //     $list->count() == 0 ? $timestamp : $timestamp = strtotime($list->last()['updated_at']);

    //     $user_notifies = ['timestamp' => (int)$timestamp,'user_notifies' => $list];

    //     return $this->succeed($user_notifies,200);
    //     // $timestamp = $request->timestamp;

    //     // if ($timestamp == 0) {
    //     //     $user_notifies = $user->notifies()->orderBy('updated_at', 'asc')->get();
    //     // }
    //     // if ($timestamp != 0) {

    //     //     $updated_at = date('Y-m-d H:i:s', $timestamp);
    //     //     $user_notifies = $user->notifies()->where('notifies.updated_at', '>', $updated_at)
    //     //         ->orderBy('notifies.updated_at', 'asc')
    //     //         ->get();
    //     // }

    //     // $user_notifies->count() == 0 ? $timestamp : $timestamp = strtotime($user_notifies->last()['updated_at']);

    //     // return $this->succeed(['timestamp' => (int) $timestamp, 'user_notifies' => $user_notifies], 200);
    // }
}
