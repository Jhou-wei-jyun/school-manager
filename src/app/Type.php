<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'type';
    protected $guarded = [
        'id'
    ];
    //type - user_type
    public function user_type()
    {
        return $this->hasOne('App\UserType', 'type_id', 'id');
    }
}
