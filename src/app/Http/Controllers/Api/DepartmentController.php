<?php

namespace App\Http\Controllers\Api;

use App\API\ApiHelper;
use App\Department;
use App\Device;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\GetList;
use App\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    use ApiHelper;

    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->error('', 401);
        }

        $departments = $request->department_id;

        $existDepartment = Department::whereIn('id', $departments)->get();

        if (count($departments) != count($existDepartment)) {
            return $this->error('Department are not exist', 400);
        }

        $user->departments()->attach($departments);

        return $this->succeed('', 200);
    }

    public function update(Request $request, $manageUser_id)
    {
        $user = $request->user();

        if (!$user) {
            return $this->error('', 401);
        }

        if ($user->position_id != 2) {
            return $this->error('permission denied!', 400);
        }

        $departments_id = $request->departments_id;

        $manageUser = User::find($manageUser_id);

        if (!$manageUser) {
            return $this->error('user invlid', 400);
        }

        $origin_departments_id = $manageUser->departments()->get()->map(function ($d) {
            return $d->id;
        });

        $removeDifferent = $origin_departments_id->diff($departments_id);

        if ($removeDifferent->count() != 0) {
            $manageUser->departments()->detach($removeDifferent);
        }

        $origin_departments_id = $manageUser->departments()->get()->map(function ($d) {
            return $d->id;
        });

        $addDifferent = collect($departments_id)->filter(function ($d) use ($origin_departments_id) {
            return !$origin_departments_id->contains($d);
        });

        if ($addDifferent->count() == 0) {
            return $this->succeed('', 200);
        }

        $manageUser->departments()->attach($addDifferent);

        return $this->succeed('', 200);
    }

    function list(Request $request)
    {
        $departments = new GetList([
            'query' => Department::query(),
            'timestamp' => $request->timestamp,
            'type' => 'departments',
        ]);

        return $this->succeed($departments, 200);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->error('', 401);
        }

        $departments = $user->departments()->get();
        $departments = $departments->map(function ($department) {
            return [
                'id' => $department->id,
                'name' => $department->name,
                'start_at' => $department->start_at,
                'finish_at' => $department->finish_at,
                'supervisor_id' => $department->supervisor_id,
            ];
        });

        return $this->succeed($departments, 200);
    }

    public function device(Request $request)
    {
        if (!$request->device_mac) {
            return $this->error('device mac is request', 400);
        }

        $device_mac = $request->device_mac;

        $device = Device::where('mac', '=', $device_mac)->first();

        if (!$device) {
            return $this->error('device is not register yet!', 400);
        }

        return $this->succeed([
            'department_id' => $device->department_id,
            'ssid' => $device->ssid,
            'password' => $device->password
        ], 200);
    }
    public function departmentName(Request $request)
    {
        $user_id = $request->user_id;
        return User::find($user_id)->department->name;
    }
}
