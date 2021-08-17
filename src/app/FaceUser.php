<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaceUser extends Model
{
    protected $guarded = [
        'id',
    ];
    public function school()
    {
        return $this->belongsTo('App\School','school_id','school_id');
    }
    public function position()
    {
        return $this->belongsTo('App\Position');
    }
    public function department()
    {
        return $this->belongsTo('App\Department');
    }
    public function face_records()
    {
        return $this->hasMany('App\FaceRecord','face_user_id');
    }
    public function spf_relationship()
    {
        return $this->hasOne('App\spf_relationship');
    }
}
