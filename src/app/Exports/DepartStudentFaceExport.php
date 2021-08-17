<?php

namespace App\Exports;

use App\User;
use App\FaceUser;
use App\School;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\Exportable;

class DepartStudentFaceExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    // use Exportable;

    protected $school_id;
    protected $department_id;
    // protected $request;

    function __construct($school_id, $department_id)
    {
        $this->school_id = $school_id;
        $this->department_id = $department_id;
    }
    // function __construct($request) {
    //     $this->$school_id = $request->$school_id;
    //     $this->$department_id = $request->$department_id;
    // }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $school = School::find($this->school_id);

        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');

        $users = $school->users()
            ->where('department_id', $this->department_id)->where("position_id", 10)->where('is_actived', true)
            ->with('profile:user_id,name')
            ->with(['tempers' => function ($temperQuery) use ($today) {
                $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'desc');
            }])
            ->with(['leaves' => function ($leaveQuery) use ($today) {
                $leaveQuery->where('leaves.created_at', 'like', '%' . $today . '%')->orderBy('id', 'desc');
            }])
            ->get();

        $users = $users->map(function ($user) {

            $collection =  collect([
                'name' => $user->profile->name,
                'tempers' => $user->tempers->first()['temperature_val'],
                'date_time' => $user->tempers->last()['record_time'],
                'leave_at' => $user->tempers->last()['record_time'] ? $user->leaves->last()['updated_at'] : null,
            ]);

            return $collection;
        });
        return $users;
    }
    // public function collection()
    // {
    //     return User::all();
    // }

    public function headings(): array
    {
        return [
            '姓名',
            '體溫',
            '到校時間',
            '離校時間'
        ];
    }
}
