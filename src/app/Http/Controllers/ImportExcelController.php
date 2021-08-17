<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Excel;
use Carbon\Carbon;
use App\API\ApiHelper;

class ImportExcelController extends Controller
{
    use ApiHelper;

    function importexcel(Request $request)
    {
        // $this->validate($request, [
        //     'select_file' => 'required|mimes:xls,xlsx'
        // ]);
        // $path = $request->file('select_file')->getRealPath();
        // $path = $request->file;
        $timezone = config('services.time_zone');
        $today_detail = Carbon::now($timezone);
        $today = Carbon::now($timezone)->format('Y-m-d');

        // $objs = json_decode($request,true);
        try {
            foreach ($request->objs as $obj)  {
                foreach ($obj as $key => $value) {
                    $insert_A[str_slug($key,'_')] = $value;
                }
                $insert_data = array(
                        'password' => 'smartcube',
                        'device_token' => 'register from HR',
                        'api_token' => 'register from HR',
                        'level_id' => 0,
                        'position_id' => 10,
                        'is_actived' => 1,
                        'onboard_date' => $today,
                        'created_at' => $today_detail,
                        'updated_at' => $today_detail,
                        'department_id' => 0,
                );
                // foreach ($insert_data as $key => $value) {
                //     $insert_B[str_slug($key,'_')] = $value;
                // }
                $result = array_merge($insert_A, $insert_data);


                User::insert($result);
                return $this->succeed('',200);
            }
            } catch ( Exception $ex ) {
                return $this->error('',400);
            }



    }
}
