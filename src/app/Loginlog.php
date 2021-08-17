<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loginlog extends Model
{
    protected $table = 'logIn_log';
    protected $guarded = [
        'id'
    ];
    public $timestamps = false;
}
