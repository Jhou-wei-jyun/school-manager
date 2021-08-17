<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\API\ApiHelper;
use App\Section;
use App\User;
use App\Department;

class SectionController extends Controller
{
    use ApiHelper;

    public function index(Request $request, $department_id)
    {
        $sections = Section::where('department_id', $request->department_id)
            ->get();

        $sections = $sections->map(function ($section) {
            return [
                'name' => $section->name,
                'title' => $section->title,
            ];
        });

        return $sections;
    }

    public function store(Request $request)
    {
        $section = new Section;
        $section->name = $request->name;
        $section->title = $request->title;
        $section->department_id = $request->department_id;
        $section->user_id = $request->user_id;
        $section->save();

        return $this->succeed('', 200);
    }
    public function getDep(Request $request)
    {
        $departments = Department::where('school_id', $request->school_id)
            ->get();

        $departments = $departments->map(function ($department) {
            return [
                'id' => $department->id,
                'name' => $department->name,
            ];
        });

        return $departments;
    }
    public function getEmp(Request $request)
    {
        $users = User::where('department_id', $request->department_id)
            ->whereNotIn('position_id', [1, 10, 20])
            ->where('is_actived', 1)
            ->get();

        $users = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

        return $users;
    }
}
