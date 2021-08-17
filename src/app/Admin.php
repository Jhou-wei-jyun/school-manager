<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class Admin extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    public static $loginRule = [
        // 'mac' => 'required|string|exists:users,mac',
        'account' => 'required|string|exists:users,account',
        // 'device_token' => 'required|string',
    ];
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
    public function teacher()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function Announcement()
    {
        return $this->hasMany('App\Announcement', 'admin_id', 'id');
    }
    public function group()
    {
        return $this->belongsTo('App\Group', 'group_id', 'group_id');
    }
    /**
     * 取得所有該人發送的通知。
     */
    public function sent_notifies()
    {
        return $this->morphMany('App\Notify', 'sent');
    }
    //level - admin
    public function level()
    {
        return $this->hasOne('App\Level', 'id', 'level_id');
    }
}
