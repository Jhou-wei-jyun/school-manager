<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Parents extends Authenticatable
{
    protected $primaryKey = 'parent_id';
    protected $table = 'parents';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'parent_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'api_token'
        // 'password', 'remember_token',
    ];
    //parent - school
    public function school()
    {
        return $this->belongsTo('App\School', 'school_id', 'school_id');
    }
    //parent - spu
    public function spu_relationship()
    {
        return $this->hasMany('App\spu_relationship', 'parent_id', 'parent_id');
    }
    public function spf_relationship()
    {
        return $this->hasMany('App\spf_relationship', 'parent_id', 'parent_id');
    }
    /**
     * 取得所有該人收到的通知。
     */
    public function notifies()
    {
        return $this->belongsToMany('App\Notify', 'parent_notifies', 'parent_id', 'notify_id')
            ->withPivot('status', 'student_id')
            ->withTimestamps();
    }
    //parent - medicine
    // public function medicines()
    // {
    //     return $this->hasMany('App\Medicine', 'parent_id', 'parent_id');
    // }
    //parent - contact
    // public function contacts()
    // {
    //     return $this->hasMany('App\Contact', 'parent_id', 'parent_id');
    // }

    /**
     * 取得所有該人發送的通知。
     */
    public function sent_notifies()
    {
        return $this->morphMany('App\Notify', 'sent');
    }
    //parent - qrcode
    public function qrcode()
    {
        return $this->hasOne('App\Qrcode', 'parent_id', 'parent_id');
    }
    //Parent - Feedback
    public function feedbacks()
    {
        return $this->morphMany('App\Feedback', 'userable');
    }
}
