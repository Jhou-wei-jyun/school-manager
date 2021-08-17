<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class spf_relationship extends Model
{
    protected $guarded = [
        'id',
    ];

    public function parent()
    {
        return $this->belongsTo('App\Parents','parent_id','parent_id');
    }
    public function faceuser()
    {
        return $this->belongsTo('App\FaceUser','face_user_id','id');
    }
}
