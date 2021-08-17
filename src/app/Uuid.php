<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Uuid extends Model
{
    protected $guarded = [
        'id',
    ];
    public $timestamps = false;
    //uuid - user_type
    public function user_type()
    {
        return $this->belongsTo('App\UserType', 'user_type_id', 'id');
    }
}
