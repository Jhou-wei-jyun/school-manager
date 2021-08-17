<?php

namespace App\Imports;

use App\User;
use App\spu_relationship;
use App\Area;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

HeadingRowFormatter::default('none');

class StudentImport implements OnEachRow, WithHeadingRow
{

    // /**
    // * @param array $row
    // *
    // * @return \Illuminate\Database\Eloquent\Model|null
    // */
    public function onRow(Row $row)
    {
        $row      = $row->toArray();

        $timezone = config('services.time_zone');
        $today = Carbon::now($timezone)->format('Y-m-d');

        $user = User::firstOrCreate(
        [
            'mac'           => $row['MAC'],
        ],
        [
            'name'          => $row['姓名'],
            // 'mac'           => $row['mac'],
            'gender'        => $row['性別'],
            'device_token'  => 'register from HR',
            'api_token'     => 'register from HR',
            'position_id'   => 10,
            'department_id' => $row['班級'],
            'school_id'     => $row['學校'],
            'onboard_date'  => $today,
        ]);
        $area = Area::get('id');
        $user ->areas()->syncWithoutDetaching($area);
        $user ->save();

        $relationship = spu_relationship::firstOrCreate(
        [
            'user_id'       => $user->id,
        ],
        [
            'parent_id'     => $row['家長'],
            'school_id'     => $row['學校'],
        ]);

        $relationship ->save();

    }

    // public function rules(): array
    // {
    //     return [
    //         'MAC' => function($attribute, $value, $onFailure) {
    //             // dd(mb_strlen($value));
    //             if (mb_strlen($value) !== 8 || ctype_alnum($value) === false) {
    //                 $onFailure('MAC發現有問題的字元或長度');
    //             }
    //         },
    //     ];
    // }
}
