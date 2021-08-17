<?php

namespace App\Exports;

use App\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AllTeacherTimeFaceExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');

        $users = User::where('school_id', $this->school_id)
            ->whereNotIn('position_id', [10, 20])->where('is_actived', true)
            ->with('position')
            ->with('profile:user_id,name')
            ->with('departments:supervisor_id,name')
            ->with(['tempers' => function ($temperQuery) use ($today) {
                $temperQuery->where('temperatures.record_time', 'like', '%' . $today . '%')->orderBy('id', 'desc');
            }])
            ->get();


        $users = $users->map(function ($user) {
            $department_name = array();
            foreach ($user->departments as $department) {
                $department_name[] = $department->name;
            }

            $collection =  collect([
                // 'id' => $user->id,
                'name' => $user->profile->name,
                // 'account' => $user->account,
                // 'phone' => $user->phone,
                // 'department' => $department_name,
                // 'mac' => $user->mac,
                // 'avatar' => $user->avatar,
                // 'gender' => $user->gender,
                // 'system' => $user->system,
                // 'onboard_date' => $user->onboard_date,
                // 'position' => $user->position->first()['name'],
                'tempers' => $user->tempers->first()['temperature_val'],
                'date_time' => $user->tempers->last()['record_time'],
                'leave_at' => $user->tempers->first()['record_time'],
            ]);
            return $collection;
        });

        return $users;
    }

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
