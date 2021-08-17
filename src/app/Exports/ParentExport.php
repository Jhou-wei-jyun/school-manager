<?php

namespace App\Exports;

use App\Parents;
use App\School;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
// use Maatwebsite\Excel\Concerns\Exportable;

class ParentExport implements FromCollection, WithHeadings, ShouldAutoSize
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

        $parents = Parents::where('school_id', '=', $this->school_id)
            ->with('spu_relationship.user.profile')
            ->get();

        $parents = $parents->map(function ($parent) {
            $student_name = $parent->spu_relationship->pluck('user.profile')->implode('name', ', ');

            $collection =  collect([
                'name' => $parent->name,
                'phone' => $parent->phone,
                'student_name' => $student_name,
            ]);

            return $collection;
        });

        return $parents;
    }
    public function columnFormats(): array
    {
        return [
            'B' => '@',
        ];
    }
    public function headings(): array
    {
        return [
            '姓名',
            '電話',
            '孩子姓名'
        ];
    }
}
