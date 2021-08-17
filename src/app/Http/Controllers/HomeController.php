<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\API\ApiHelper;
use App\Admin;
use App\School;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    use ApiHelper;
    // public function __construct()
    // {
    //     $this->middleware('auth');

    //     // $this->middleware(function ($request, $next) {
    //     //     // $this->user = Auth::user(); // here the user should exist from the session
    //     //     return $next($request);
    //     // });
    // }
    public function record()
    {
        return view('record');
    }

    public function department()
    {
        return view('department');
    }

    public function departmentDetail()
    {
        return view('departmentDetail');
    }
    public function device()
    {
        return view('device');
    }

    public function employee()
    {
        return view('employee');
    }
    public function teacher()
    {
        return view('teacher');
    }
    public function newEmployee()
    {
        return view('newEmployee');
    }

    public function material()
    {
        return view('material');
    }

    public function notify()
    {
        return view('notify');
    }
    public function chart()
    {
        return view('chart');
    }

    public function property()
    {
        return view('property');
    }

    public function area()
    {
        return view('area');
    }

    public function setup()
    {
        return view('setup');
    }
    public function env()
    {
        return view('env');
    }

    public function webDoc()
    {
        return view('webApiDoc');
    }

    public function appDoc()
    {
        return view('appApiDoc');
    }

    public function serveDoc()
    {
        return view('serveSetup');
    }

    public function parents()
    {
        return view('parents');
    }

    public function mainhome()
    {
        return view('mainhome');
    }
    public function account()
    {
        return view('account');
    }
    public function about()
    {
        return view('about');
    }
    public function announce()
    {
        return view('announce');
    }
    public function becon()
    {
        return view('becon');
    }
    public function attendance()
    {
        return view('attendance');
    }
    public function right()
    {
        return view('Right');
    }
    public function contact()
    {
        return view('contact');
    }
    public function album()
    {
        return view('album');
    }
    public function albumDetail()
    {
        return view('albumDetail');
    }
    public function albumChild()
    {
        return view('albumChild');
    }
    public function asyncMachines()
    {
        return view('asyncMachines');
    }
    public function optionSetting()
    {
        return view('optionSetting');
    }
    public function medicine()
    {
        return view('medicine');
    }
    public function leave()
    {
        return view('leave');
    }
    public function question()
    {
        return view('question');
    }

    // public function login(Request $request)
    // {
    //     $account = $request->account;
    //     $password = $request->password;

    //     if (!$account || !$password) {
    //         return $this->error('', 400);
    //     }

    //     $admin = Admin::where('account', $account)->first();

    //     if (!$admin || $password != 'smartcube' || $admin->level == 0) {
    //         return $this->error('', 403);
    //     }
    //     $school_info = School::find($admin->school_id);
    //     if ($admin->group_id == null) {
    //         return $this->error('權限不足', 403);
    //     }

    //     $admin->update([
    //         'api_token' => Str::random(60),
    //     ]);

    //     activity()
    //         ->causedBy($admin)
    //         ->performedOn($admin)
    //         ->withProperties(['type' => 'login'])
    //         ->log('Web登入');

    //     $data = [
    //         'id' => $admin->id,
    //         'name' => $admin->name,
    //         'api_token' => $admin->api_token,
    //         'group_id' => $admin->group_id,
    //         'avatar' => null,
    //         'teacher_id' => $admin->user_id,
    //         'school' => $admin->school_id,
    //         'school_info' => $school_info,
    //         'teacher_type' => $school_info->teacher_type,
    //         'student_type' => $school_info->student_type,
    //     ];
    //     Auth::login($admin);
    //     return $this->succeed($data, 200);
    // }
}
