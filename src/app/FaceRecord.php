<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaceRecord extends Model
{
    protected $guarded = [
        'id',
    ];
    public function faceuser()
    {
        return $this->belongsTo('App\FaceUser','face_user_id');
    }
}
