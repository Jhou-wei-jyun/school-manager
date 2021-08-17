<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sdd_relationship extends Model
{
    protected $guarded = [
        'id'
    ];
    //school - depart - department
    public function school()
    {
        return $this->belongsTo('App\School','school_id','school_id');
    }
    //school - depart - department
    public function depart()
    {
        return $this->belongsTo('App\Depart','depart_id','depart_id');
    }
    //school - depart - department
    public function department()
    {
        return $this->belongsTo('App\Department','department_id','id');
    }
}
