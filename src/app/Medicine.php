<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    // public function parent()
    // {
    //     return $this->belongsTo('App\Parents', 'parent_id', 'parent_id');
    // }
    // public function teacher()
    // {
    //     return $this->belongsTo('App\User', 'teacher_id');
    // }
    public function photos()
    {
        return $this->morphMany('App\Photo', 'imageable');
        // return $this->hasMany('App\Photo', 'medicine_id', 'id');
    }
}
