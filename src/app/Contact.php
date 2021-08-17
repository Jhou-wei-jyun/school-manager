<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [
        'id',
    ];

    public function sections()
    {
        return $this->belongsToMany('App\Section')
            ->withTimestamps();
    }
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
    }
    public function files()
    {
        return $this->morphMany('App\File', 'imageable');
    }
}
