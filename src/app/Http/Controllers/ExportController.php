<?php

namespace App\Http\Controllers;

//Export
use App\Exports\DepartStudentExport;
use App\Exports\DepartStudentFaceExport;
use App\Exports\AllStudentExport;
use App\Exports\AllStudentFaceExport;
use App\Exports\AllTeacherExport;
use App\Exports\AllTeacherTimeExport;
use App\Exports\AllTeacherFaceExport;
use App\Exports\AllTeacherTimeFaceExport;
use App\Exports\ParentExport;
//Sample Export
use App\Exports\DepartStudentSampleExport;
use App\Exports\AllStudentSampleExport;
use App\Exports\TeacherSampleExport;
use App\Exports\ParentSampleExport;
//
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function depart_student_becon_face_export(Request $request)
    {
        $school_id = $request->school_id;
        $department_id = $request->department_id;


        return Excel::download(new DepartStudentExport($school_id, $department_id), 'users_becon_face-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }
    public function depart_student_face_export(Request $request)
    {
        $school_id = $request->school_id;
        $department_id = $request->department_id;


        return Excel::download(new DepartStudentFaceExport($school_id, $department_id), 'users_face-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }
    public function all_student_becon_face_export(Request $request)
    {
        $school_id = $request->school_id;


        return Excel::download(new AllStudentExport($school_id), 'all_students_becon_face-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }
    public function all_student_face_export(Request $request)
    {
        $school_id = $request->school_id;


        return Excel::download(new AllStudentFaceExport($school_id), 'all_students_face-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }
    public function all_teache_becon_facer_export(Request $request)
    {
        $school_id = $request->school_id;


        return Excel::download(new AllTeacherExport($school_id), 'all_teachers_becon_face-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }
    public function all_teacher_face_export(Request $request)
    {
        $school_id = $request->school_id;


        return Excel::download(new AllTeacherFaceExport($school_id), 'all_teachers_face-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }
    public function all_teacher_time_becon_facer_export(Request $request)
    {
        $school_id = $request->school_id;


        return Excel::download(new AllTeacherTimeExport($school_id), 'all_teachers_time_becon_face-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }
    public function all_teacher_time_facer_export(Request $request)
    {
        $school_id = $request->school_id;


        return Excel::download(new AllTeacherTimeFaceExport($school_id), 'all_teachers_time_face-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }

    public function parent_export(Request $request)
    {
        $school_id = $request->school_id;

        return Excel::download(new ParentExport($school_id), 'Parent-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new ParentExport($school_id), 'users.pdf');
    }

    //Sample Export
    public function departstudentsample(Request $request)
    {
        $school_id = $request->school_id;
        $department_id = $request->department_id;

        return Excel::download(new DepartStudentSampleExport($school_id, $department_id), 'Sample-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }

    public function allstudentsample(Request $request)
    {
        $school_id = $request->school_id;

        return Excel::download(new AllStudentSampleExport($school_id), 'Sample-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }

    public function teachersample(Request $request)
    {
        $school_id = $request->school_id;

        return Excel::download(new TeacherSampleExport($school_id), 'Sample-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }

    public function parentsample(Request $request)
    {
        $school_id = $request->school_id;

        return Excel::download(new ParentSampleExport($school_id), 'Sample-' . date("Y-m-d") . '.xlsx');
        // return Excel::download(new DepartStudentExport($school_id, $department_id), 'users.pdf');
    }

}
