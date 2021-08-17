<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'leaves';
    protected $guarded = [
        'id',
    ];
    public function students()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
