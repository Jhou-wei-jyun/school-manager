<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'start_at', 'finish_at', 'supervisor_id', 'photo', 'avatar'
    ];

    public static $registerDepartmentRule = [
        'name' => 'required|string',
        'supervisor' => 'required|integer',
    ];

    // public function supervisor()
    // {
    //     return $this->belongsTo('App\User','supervisor_id');
    // }

    public function students()
    {
        return $this->hasMany('App\User');
    }
    public function teacher()
    {
        return $this->belongsTo('App\User', 'supervisor_id');
    }
    public function school()
    {
        return $this->belongsTo('App\School', 'school_id', 'school_id');
    }


    // public function materials()
    // {
    //     return $this->hasMany('App\Material');
    // }
    public function areas()
    {
        return $this->belongsToMany('App\Area', 'department_area')
            ->withTimestamps();
    }
    public function sections()
    {
        return $this->hasMany('App\Section');
    }
    //depart - school - department
    public function sdd_relationship()
    {
        return $this->hasOne('App\sdd_relationship', 'department_id', 'id');
    }
    //department - album
    public function albums()
    {
        return $this->hasMany('App\Album', 'department_id', 'id');
    }
    //department - option
    public function options()
    {
        return $this->hasMany('App\Option', 'department_id', 'id');
    }
}
