<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    protected $guarded = [
        'id',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_notifies')
            ->withPivot('status', 'student_id')
            ->withTimestamps();
    }
    public function parents()
    {
        return $this->belongsToMany('App\Parents', 'parent_notifies', 'notify_id', 'parent_id')
            ->withPivot('status', 'student_id')
            ->withTimestamps();
    }

    /**
     * 取得發送者的模型。
     */
    public function sent()
    {
        return $this->morphTo();
    }
}
