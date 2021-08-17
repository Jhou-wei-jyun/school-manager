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

class TeacherImport implements OnEachRow, WithHeadingRow
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


        // if (mb_strlen($row['MAC']) < 11) {
        //     $message = 'Mac發現有問題的字元或長度';
        //     return $this->error($message, 400);
        // }

        // if (mb_strlen($row['電話']) < 10) {
        //     $message = 'Phone發現有問題的字元或長度';
        //     return $this->error($message, 400);
        // }

        $user_mac_arr = str_split($row['MAC'] , 2);
        $user_mac = join(':', $user_mac_arr);

        $user = User::firstOrCreate(
        [
            'phone'         => $row['電話'],
        ],
        [
            'name'          => $row['姓名'],
            'mac'           => $user_mac,
            'gender'        => $row['性別'],
            'device_token'  => 'register from HR',
            'api_token'     => 'register from HR',
            'position_id'   => $row['職稱'],
            'school_id'     => $row['學校'],
            'onboard_date'  => $today,
        ]);
        $area = Area::get('id');
        $user ->areas()->syncWithoutDetaching($area);
        $user ->save();


    }

    // public function rules(): array
    // {
    //     return [
    //         'MAC' => function($attribute, $value, $onFailure) {
    //             if (mb_strlen($value) !== 0) {
    //                 if (mb_strlen($value) !== 8 || ctype_alnum($value) === false) {
    //                     $onFailure('MAC發現有問題的字元或長度');
    //                 }
    //             }
    //         },

    //         '電話' => function($attribute, $value, $onFailure) {
    //             if (mb_strlen($value) !== 10 || ctype_digit($value) === false) {
    //                  $onFailure('Phone發現有問題的字元或長度');
    //             }
    //         },
    //     ];
    // }
}
