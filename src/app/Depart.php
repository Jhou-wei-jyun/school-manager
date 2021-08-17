<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depart extends Model
{
    protected $primaryKey = 'depart_id';
    protected $guarded = [
        'depart_id'
    ];
    //depart - school - department
    public function sdd_relationship()
    {
        return $this->hasMany('App\sdd_relationship', 'depart_id', 'depart_id');
    }
    //depart - school
    public function school()
    {
        return $this->belongsTo('App\School','school_id', 'school_id');
    }
}
