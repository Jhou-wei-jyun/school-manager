<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Employee extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $today = Carbon::now()->format('Y-m-d');
    $firstRecord = $this->records()->join('departments','records.department_id','=','departments.id')->where('records.date_time','like','%'.$today.'%')->first();
    $lastRecord = $this->records()->join('departments','records.department_id','=','departments.id')->where('records.date_time','like','%'.$today.'%')->get()->last();
    return [
        'employee_id' => $this['id'],
        'employee_name' => $this['name'],
        'department_id' => $lastRecord != null ? $lastRecord['department_id'] : $firstRecord['department_id'],
        'department' => $lastRecord != null ? $lastRecord['name'] : $firstRecord['name'],
        'started_work_at' => $firstRecord['start_timestamp'],
        'finished_work_at' => $lastRecord['leave_timestamp'],
        'employee_statu_id' => $firstRecord['statu_id']
    ];
  }
}
