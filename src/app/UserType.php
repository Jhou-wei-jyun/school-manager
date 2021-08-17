<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $guarded = [
        'id'
    ];
    public $timestamps = false;
    protected $table = 'user_type';

    //user - user_type
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    //type - user_type
    public function type()
    {
        return $this->belongsTo('App\Type', 'type_id', 'id');
    }
    //uuid - user_type
    public function uuid()
    {
        return $this->hasOne('App\Uuid', 'user_type_id', 'id');
    }
    //mac - user_type
    public function mac()
    {
        return $this->hasOne('App\Mac', 'user_type_id', 'id');
    }
}
