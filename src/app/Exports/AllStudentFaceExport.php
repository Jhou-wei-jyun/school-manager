<?php

namespace App\Exports;

use App\User;
use App\School;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\Exportable;

class AllStudentFaceExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $school_id;

    function __construct($school_id)
    {
        $this->school_id = $school_id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $school = School::find($this->school_id);

        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');

        $users = $school->users()
            ->where("position_id", 10)->where('is_actived', true)
            ->with('profile:user_id,name')
            ->with('department:id,name')
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
                'department' => $user->department == null ? null : $user->department->name,
                'tempers' => $user->tempers->first()['temperature_val'],
                'date_time' => $user->tempers->last()['record_time'],
                'leave_at' => $user->tempers->last()['record_time'] ? $user->leaves->last()['updated_at'] : null,
            ]);

            return $collection;
        });

        return $users;
    }

    public function headings(): array
    {
        return [
            '姓名',
            '班級',
            '體溫',
            '到校時間',
            '離校時間'
        ];
    }
}
