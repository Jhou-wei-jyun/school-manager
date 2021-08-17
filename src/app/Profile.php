<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $primaryKey = 'profile_id';
    protected $guarded = [
        'profile_id'
    ];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
