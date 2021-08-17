<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    protected $guarded = [
        'id'
    ];

    //avatar(department)-school
    public function school()
    {
        return $this->belongsTo('App\School', 'school_id', 'school_id');
    }
}
