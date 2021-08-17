<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Examination extends Model
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

    public function material()
    {
        return $this->belongsTo('App\Material');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_examinations')
        ->withPivot('score')
        ->withTimestamps();
    }
}
