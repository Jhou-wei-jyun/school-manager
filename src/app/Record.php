<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'date_time','leave_at','statu_id','area_id','item_id',
    ];

    public static $recordRule = [
        // 'mac' => 'required|exists:users,mac' || 'required|exists:items,mac',
        'area_id'=> 'required|exists:areas,id',
        'date_time' => 'required',
        'rssi' => 'required|integer',
    ];


    public function statu()
    {
        return $this->belongsTo('App\Statu');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function area()
    {
        return $this->belongTo('App\Area');
    }
}
