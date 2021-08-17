<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url', 'department_id', 'active'
    ];

    // public function department()
    // {
    //     return $this->belongsTo('App\Department');
    // }

    public function examinations()
    {
        return $this->hasMany('App\Examination');
    }
}
