<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\API\ApiHelper;
use App\Department;
use App\Http\Resources\Api\Employee;
use App\User;
use App\Http\Resources\Api\Record as RecordResource;

class SupervisorController extends Controller
{
   use ApiHelper;

   public function employees(Request $request)
   {
       $user = $request->user();

       if ($user->position_id != 2){
           return $this->error('You`re not supervisor',400);
       }

       $department = Department::where('supervisor_id','=',$user->id)->first();

       $employees = $department->users()->get();

       $employees = $employees->map(function($employee){
           return new Employee($employee);
       });

       return $this->succeed($employees,200);
   }

   public function employeeDetails(Request $request, $employee_id,$timestamp)
   {
     $user = $request->user();

     if ($user->position_id != 2){
        return $this->error('You`re not supervisor',400);
     }

     $employee = User::find($employee_id);

     if (!$employee){
         return $this->error('',400);
     }

    $records = new RecordResource(['user'=>$employee,'timestamp'=>$timestamp]);

    collect($records)->count() == 0 ? $timestamp : $timestamp = strtotime(collect($records)->last()['updated_at']);

    return $this->succeed([
        'timestamp' => (int)$timestamp,
        'records' => $records
    ], 200);
   }
}
