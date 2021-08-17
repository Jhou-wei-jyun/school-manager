<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OriRecord extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rssi', 'date_time','date_long','area_id','tag','bat','mac'
    ];
}
