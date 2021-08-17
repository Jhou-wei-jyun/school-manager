<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class spu_relationship extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];
    //user - relation - parent
    public function parent()
    {
        return $this->belongsTo('App\Parents','parent_id','parent_id');
    }
    //user - relation - parent
    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
    //user - school - parent
    public function school()
    {
        return $this->belongsTo('App\School','school_id','school_id');
    }
}

