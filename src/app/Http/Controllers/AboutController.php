<?php

namespace App\Http\Controllers;

use App\API\ApiHelper;
use Illuminate\Http\Request;
use App\School;
use DB;

class AboutController extends Controller
{
    use ApiHelper;

    public function index(Request $request)
    {
        $school = School::find($request->school_id);
        return $school;
    }
    public function updateimg(Request $request)
    {
        // $logo = base64_encode(file_get_contents($request->school_logo));
        $logo = $request->school_logo;
        $school = School::find($request->school_id);

        $school->school_log = $logo;
        $school->save();
        return $logo;
        // return $this->succeed('',200);
    }
    public function updateinfo(Request $request)
    {
        // $logo = base64_encode(file_get_contents($request->school_logo));
        $info = $request->school_info;
        $school = School::find($request->school_id);

        $school->school_info = $info;
        $school->save();
        return $info;
        // return $this->succeed('',200);
    }
    public function refresh_info(Request $request)
    {

        $school_info = School::where('school_id',$request->school_id)->get(
            ['school_name','school_ename','school_info','school_log','school_url']
        )->first();


        $data = [
            'school_info' => $school_info,
        ];

        return $this->succeed($data,200);
    }
}
