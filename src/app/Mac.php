<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mac extends Model
{
    protected $guarded = [
        'id'
    ];
    public $timestamps = false;
    //mac - user_type
    public function user_type()
    {
        return $this->belongsTo('App\UserType', 'user_type_id', 'id');
    }
}
