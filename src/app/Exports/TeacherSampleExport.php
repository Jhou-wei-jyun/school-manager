<?php

namespace App\Exports;

use App\User;
use App\Position;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class TeacherSampleExport implements WithEvents, WithHeadings
{
    protected $school_id;

    function __construct($school_id)
    {
        $this->school_id = $school_id;
    }

    public function headings(): array
    {
        return [
            '姓名',
            'MAC',
            '性別',
            '電話',
            '職稱',
            '學校',
        ];
    }

    public function registerEvents(): array
    {
        return [
            // handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {


                // get layout counts (add 1 to rows for heading row)
                // $row_count = $this->results->count() + 1;
                $row_count = 50;
                // $column_count = count($this->results[0]->toArray());
                $column_count = 6;

                // set dropdown column
                $drop_column_mac = 'B';
                $drop_column_gender = 'C';
                $drop_column_phone = 'D';
                $drop_column_position = 'E';
                $drop_column_school = 'F';

                //set mac column
                $spreadsheet = $event->sheet->getStyle("{$drop_column_mac}2")->getNumberFormat();
                $spreadsheet->setFormatCode('@');

                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getStyle("{$drop_column_mac}{$i}")->getNumberFormat()->setFormatCode('@');
                }

                $validation = $event->sheet->getCell("{$drop_column_mac}2")->getDataValidation();
                $validation->setShowInputMessage(true);
                $validation->setPromptTitle('輸入MAC');
                $validation->setPrompt('不含：之英數共8碼');
                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column_mac}{$i}")->setDataValidation(clone $validation);
                }

                //set phone column
                $spreadsheet = $event->sheet->getStyle("{$drop_column_phone}2")->getNumberFormat();
                $spreadsheet->setFormatCode('@');

                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getStyle("{$drop_column_phone}{$i}")->getNumberFormat()->setFormatCode('@');
                }

                $validation = $event->sheet->getCell("{$drop_column_phone}2")->getDataValidation();
                $validation->setShowInputMessage(true);
                $validation->setPromptTitle('輸入市話');
                $validation->setPrompt('09XXXXXXXX共10碼');
                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column_phone}{$i}")->setDataValidation(clone $validation);
                }

                //set gender column
                $options_gender = [
                    1,
                    2,
                ];

                $validation = $event->sheet->getCell("{$drop_column_gender}2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('選取性別');
                $validation->setPrompt('1 : 男 , 2 : 女');
                $validation->setFormula1(sprintf('"%s"', implode(',', $options_gender)));
                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column_gender}{$i}")->setDataValidation(clone $validation);
                }

                //set position column
                $positions = Position::whereNotIn('id', [1, 10, 20])->get();
                $position_name = array();
                $position_id = array();
                foreach ($positions as $position) {
                    $position_name[] = $position->name;
                    $position_id[] = $position->id;
                }

                $strlist = '';
                foreach (array_map(NULL, $position_id, $position_name) as [$id, $name]) {
                    $str = sprintf('%s : %s , ', $id, $name);
                    $strlist .= $str;
                }

                $validation = $event->sheet->getCell("{$drop_column_position}2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('選取職稱');
                $validation->setPrompt($strlist);
                $validation->setFormula1(sprintf('"%s"', implode(',', $position_id)));
                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column_position}{$i}")->setDataValidation(clone $validation);
                }


                //set school column
                $validation = $event->sheet->getCell("{$drop_column_school}2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('選取學校');
                $validation->setPrompt('');
                $validation->setFormula1(sprintf('"%s"', $this->school_id));
                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column_school}{$i}")->setDataValidation(clone $validation);
                }

                // set columns to autosize
                for ($i = 1; $i <= $column_count; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setWidth(15);
                }
            },
        ];
    }
}
