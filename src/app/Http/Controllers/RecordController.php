<?php

namespace App\Http\Controllers;

use App\Area;
use App\Department;
use App\Http\Controllers\Controller;
use App\Position;
use App\Record;
use App\Statu;
use App\User;
use App\Parents;
use App\spu_relationship;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        // $users = User::get();
        // $all = $users->map(function($u){
        //     $list = Redis::hgetall($u->id);
        //     $list = collect($list)->map(function($k)use($u){
        //         $d = json_decode($k);
        //         return [
        //             'user_name' => $u->name,
        //             'date_time' => $d->date,
        //             'department' => Department::find($d->department_id)['name'],
        //             'rssi' => $d->rssi,
        //             'times' => $d->times,
        //             'end_date' => $d->end_date,
        //         ];
        //     });
        //     return $list;
        // });
        // return $all->collapse()->sortKeysDesc()->values();
        $timezone = config('services.time_zone');
        $perPage = (int) $request->input('perPage', '50');
        $sortBy = $request->input('sortBy', 'records.id');
        $order = $request->input('order', 'desc');

        $today = Carbon::now($timezone)->format('Y-m-d');
        $paginate = Record::join('areas', 'records.area_id', '=', 'areas.id')
            ->where('records.date_time', 'like', '%' . $today . '%')
            ->with('user')->with('item')->orderBy('records.id', 'desc')->paginate(20);
        // $paginate = $paginate->data->map(function($record){
        //     $user = User::find($record->user_id);
        //     $user_departments = $user->departments();

        //     $user_departments->filter(function($user_department) use($record){
        //         return $user_department->id = $record->department_id;
        //     });
        // });

        return $paginate;
    }

    public function search(Request $request)
    {
        $user_name = $request->value;
        $today = Carbon::now()->format('Y-m-d');
        $user = User::where('users.name', 'like', '%' . $user_name . '%')
            ->join('records', 'records.user_id', 'users.id')
            ->where('records.date_time', 'like', '%' . $today . '%')
            ->join('departments', 'departments.id', 'records.department_id')
            ->select('users.*', 'records.*')
        // ->get();
            ->orderBy('records.id', 'desc')->paginate(20);
        return $user;
    }

    public function show(Request $request)
    {
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');

        $user = User::find($request->user_id);

        $records = $user->records()->where('records.date_time', 'like', '%' . $today . '%')->orderBy('records.leave_at','desc')->get();

        $records = $records->map(function ($record) {

            $area = Area::find($record->area_id);
            $start = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($record->date_time)->format('Y-m-d H:i:s'));
            $leave = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($record->leave_at)->format('Y-m-d H:i:s'));
            $range = $start->diffInMinutes($leave);

            $start = $start->format('H:i');
            $leave = $leave->format('H:i');

            if ($record->leave_at == null) {
                $leave = '';
                $range = 0;
            }

            return [
                'record' => $record,
                'id' => $record->id,
                'stay_period' => $start . ' - ' . $leave,
                'stay_time' => $range / 60,
                'area' => $area->name,
                'statu' => $record->statu_id,
            ];
        })->filter(function ($record) {
            return $record['stay_time'] > 0.0;
        })->values();

        // ->filter(function($record){
        //     return $record['stay_time'] > 0.0;
        // })

        return $records;

    }
}
