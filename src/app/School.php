<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $primaryKey = 'school_id';
    protected $guarded = [
        'school_id'
    ];
    public $timestamps = false;


    //user-school
    public function users()
    {
        return $this->hasMany('App\User', 'school_id', 'school_id');
    }
    //department-school
    public function departments()
    {
        return $this->hasMany('App\Department', 'school_id', 'school_id');
    }
    //depart-school
    public function departs()
    {
        return $this->hasMany('App\Depart', 'school_id', 'school_id');
    }
    //avatar(department)-school
    public function avatars()
    {
        return $this->hasMany('App\Avatar', 'school_id', 'school_id');
    }
    //parent-school
    public function parents()
    {
        return $this->hasMany('App\Parents', 'school_id', 'school_id');
    }
    //parent - spu
    public function spu_relationship()
    {
        return $this->hasMany('App\spu_relationship', 'school_id', 'school_id');
    }


    public function sm_relationship()
    {
        return $this->hasOne('App\sm_relationship', 'school_id');
    }
    public function areas()
    {
        return $this->hasMany('App\Area', 'school_id', 'school_id');
    }
    public function devices()
    {
        return $this->hasMany('App\Device', 'school_id', 'school_id');
    }
    public function machines()
    {
        return $this->hasMany('App\Machine', 'school_id', 'school_id');
    }
    //school - relation - depart$department
    public function sdd_relationship()
    {
        return $this->hasMany('App\sdd_relationship', 'school_id', 'school_id');
    }
    //school - setting
    public function setting()
    {
        return $this->hasOne('App\Setting', 'school_id', 'school_id');
    }
}
