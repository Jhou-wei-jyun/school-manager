<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TryLoginlog extends Model
{
    protected $table = 'tryLogIn_log';
    protected $guarded = [
        'id'
    ];
    public $timestamps = false;
}
