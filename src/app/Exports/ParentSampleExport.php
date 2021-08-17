<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class ParentSampleExport implements WithEvents, WithHeadings
{
    protected $school_id;

    function __construct($school_id) {
            $this->school_id = $school_id;
    }

    public function headings(): array
    {
        return [
            '姓名',
            '電話',
            '關係',
            '學校',
        ];
    }
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        return [
            // handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {


                // get layout counts (add 1 to rows for heading row)
                // $row_count = $this->results->count() + 1;
                $row_count = 50;
                // $column_count = count($this->results[0]->toArray());
                $column_count = 6;

                // set dropdown column
                $drop_column_phone = 'B';
                $drop_column_title = 'C';
                $drop_column_school = 'D';

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
                $options_title = [
                    'mother',
                    'father',
                    'grandmother',
                    'grandfather'
                ];

                $validation = $event->sheet->getCell("{$drop_column_title}2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST );
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION );
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('選取關係');
                $validation->setPrompt('');
                $validation->setFormula1(sprintf('"%s"',implode(',',$options_title)));
                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column_title}{$i}")->setDataValidation(clone $validation);
                }

                //set school column
                $validation = $event->sheet->getCell("{$drop_column_school}2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST );
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION );
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('選取學校');
                $validation->setPrompt('');
                $validation->setFormula1(sprintf('"%s"',$this->school_id));
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
