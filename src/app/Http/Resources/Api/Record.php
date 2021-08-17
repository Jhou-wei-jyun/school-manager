<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\User;

class Record extends JsonResource
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
        $query = $this['user']->records()->join('areas','areas.id','=','records.area_id');

        if ($this['timestamp'] != 0){
            $updated_at = date('Y-m-d H:i:s',$this['timestamp']);
            $query = $query->where('records.updated_at','>',$updated_at);
        }

        $departments_id = $query->select('records.*','areas.name as area_name')
            ->orderBy('updated_at', 'asc')->get();

        $records = $departments_id->map(function($v){
            return [
                    'record_id' => $v->id,
                    'area_id' => $v->area_id,
                    'area' => $v->area_name,
                    'started_work_at' => $v->start_timestamp,
                    'finished_work_at' => $v->leave_timestamp,
                    'updated_at' => $v->updated_at
            ];
        });

        return $records;

    }
}
