<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $guarded = [
        'id',
    ];

    public function school()
    {
        return $this->belongsTo('App\School','school_id','school_id');
    }
}
