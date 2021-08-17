<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GetList extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $timestamp = $this['timestamp'];
    $query = $this['query'];
    $type_name = $this['type'];

    if ($timestamp == 0){

        $list =  $query->orderBy('updated_at', 'asc')->get();
    }
    if ($timestamp != 0){

        $updated_at = date('Y-m-d H:i:s',$timestamp);
        $list = $query->where('updated_at','>',$updated_at)
            ->orderBy('updated_at', 'asc')
            ->get();
    }

    if ($type_name == 'areas'){
        $list = $list->map(function($area){
            $area->location_photo_social_0 = $area->location_photo_social_0_url;
            $area->location_photo_social_1 = $area->location_photo_social_1_url;
            $area->location_photo_social_2 = $area->location_photo_social_2_url;
            $area->location_emergency_exit = $area->location_emergency_exit_url;
            $area->lottie = $area->lottie_url;
            return  $area;
        });
    }

    $list->count() == 0 ? $timestamp : $timestamp = strtotime($list->last()['updated_at']);

    return ['timestamp' => (int)$timestamp,$type_name => $list];
  }
}
