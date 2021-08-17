<?php

namespace App\Http\Controllers;

use App\API\ApiHelper;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentImport;
use App\Imports\TeacherImport;
use App\Imports\ParentImport;

class ImportController extends Controller
{
    use ApiHelper;

    public function studentimport(Request $request)
    {
        $file = $request->file;

        Excel::import(new StudentImport, $file);
        return $this->succeed('', 200);
    }

    public function teacherimport(Request $request)
    {
        $file = $request->file;

        Excel::import(new TeacherImport, $file);
        return $this->succeed('', 200);
    }

    public function parentimport(Request $request)
    {
        $file = $request->file;

        Excel::import(new ParentImport, $file);
        return $this->succeed('', 200);
    }
}
