<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at'  => 'datetime',
    ];

    public static $registerRule = [
        'name' => 'required|string',
        'gender' => 'numeric|max:4',
        'mac' => 'required|string|unique:users,mac',
        'position_id' => 'required|integer',
    ];

    public static $loginRule = [
        // 'mac' => 'required|string|exists:users,mac',
        'account' => 'required|string|exists:users,account',
        // 'device_token' => 'required|string',
    ];

    public function getAvatarUrlAttribute()
    {
        $storage = config('services.storage');
        if ($this->avatar) {
            return Storage::disk($storage)->url($this->avatar);
        }
    }
    //user - school
    public function school()
    {
        return $this->belongsTo('App\School', 'school_id', 'school_id');
    }
    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id', 'id');
    }
    public function departments()
    {
        return $this->hasMany('App\Department', 'supervisor_id', 'id');
    }

    public function areas()
    {
        return $this->belongsToMany('App\Area', 'user_areas')
            ->withTimestamps();
    }
    /**
     * 取得所有該人收到的通知。
     */
    public function notifies()
    {
        return $this->belongsToMany('App\Notify', 'user_notifies')
            ->withPivot('status', 'student_id')
            ->withTimestamps();
    }


    public function records()
    {
        return $this->hasMany('App\Record');
    }

    public function tempers()
    {
        return $this->hasMany('App\Temperature');
    }

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function examinations()
    {
        return $this->belongsToMany('App\Examination', 'user_examinations')
            ->withPivot('score')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_users')
            ->withTimestapms();
    }

    public function level()
    {
        return $this->belongTo('App\Level');
    }

    public function sections()
    {
        return $this->hasMany('App\Section');
    }
    public function contacts()
    {
        return $this->hasMany('App\Contact', 'user_id');
    }
    // public function teachercontacts()
    // {
    //     return $this->hasMany('App\Contact', 'teacher_id');
    // }
    public function medicines()
    {
        return $this->hasMany('App\Medicine', 'user_id');
    }
    // public function teachermedicines()
    // {
    //     return $this->hasMany('App\Medicine', 'teacher_id');
    // }
    //user - admin
    public function admin()
    {
        return $this->hasOne('App\Admin', 'user_id', 'id');
    }
    //user - user_type
    public function user_type()
    {
        return $this->hasOne('App\UserType', 'user_id', 'id');
    }
    //user - profile
    public function profile()
    {
        return $this->hasOne('App\Profile', 'user_id', 'id');
    }
    //user - relation - parent
    public function spu_relationship()
    {
        return $this->hasOne('App\spu_relationship', 'user_id', 'id');
    }
    //user - chat_message
    public function teacher_messages()
    {
        return $this->hasMany('App\Chat_message', 'teacher_id', 'id');
    }
    public function student_messages()
    {
        return $this->hasMany('App\Chat_message', 'user_id', 'id');
    }
    //user - achievement
    public function achievements()
    {
        return $this->hasMany('App\Achievement', 'user_id', 'id');
    }
    /**
     * 取得所有被賦予該人物標籤的圖片。
     */
    public function photos()
    {
        return $this->morphedByMany('App\Photo', 'user_taggable');
    }
    //user - leaves
    public function leaves()
    {
        return $this->hasMany('App\Leave', 'user_id', 'id');
    }
    /**
     * 取得所有該人發送的通知。
     */
    public function sent_notifies()
    {
        return $this->morphMany('App\Notify', 'sent');
    }
    //user - attendance
    public function attendance()
    {
        return $this->hasMany('App\Attendance', 'user_id', 'id');
    }
    //Teacher - Feedback
    public function feedbacks()
    {
        return $this->morphMany('App\Feedback', 'userable');
    }
}
