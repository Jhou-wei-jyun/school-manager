<?php

namespace App\Imports;

use App\Parents;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;


HeadingRowFormatter::default('none');

class ParentImport implements OnEachRow, WithHeadingRow
{

    // /**
    // * @param array $row
    // *
    // * @return \Illuminate\Database\Eloquent\Model|null
    // */
    public function onRow(Row $row)
    {
        $row      = $row->toArray();

        $user = Parents::firstOrCreate(
        [
            'phone'         => $row['電話'],
        ],
        [
            'name'          => $row['姓名'],
            'ename'          => $row['姓名'],
            'title'          => $row['關係'],
            'school_id'     => $row['學校'],
        ]);

        $user ->save();

    }

    // public function rules(): array
    // {
    //     return [
    //         '電話' => function($attribute, $value, $onFailure) {

    //             dd($value);
    //             // dd((mb_strlen($value) != 10));
    //             if (mb_strlen($value)!==10) {
    //                 $onFailure('Phone發現有問題的長度');
    //             }

    //             // dd($value);
    //             // if (!ctype_digit($value)) {
    //             //     $onFailure('Phone發現有問題的字元');
    //             // }
    //         },
    //     ];
    // }
}
