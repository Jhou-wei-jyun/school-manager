<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $primaryKey = 'achievement_id';
    protected $guarded = [
        'achievement_id',
    ];
    //user - achievement
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
