<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $guarded = [
        'id',
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contact')
            ->withTimestamps();
    }
    public function department()
    {
        return $this->belongsTo('App\Department');
    }
}
