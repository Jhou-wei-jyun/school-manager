<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    public function area()
    {
        return $this->belongsTo('App\Area');
    }
    public function school()
    {
        return $this->belongsTo('App\School','school_id','school_id');
    }

    // public function records()
    // {
    //     return $this->hasMany('App\Record');
    // }
}
