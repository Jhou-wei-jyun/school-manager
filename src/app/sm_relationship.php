<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sm_relationship extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id','mechanical'
    ];
}

