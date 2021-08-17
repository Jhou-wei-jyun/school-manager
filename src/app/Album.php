<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $primaryKey = 'album_id';
    protected $guarded = [
        'album_id',
    ];
    //department - album
    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id', 'id');
    }
    //photo - album
    public function photos()
    {
        return $this->morphMany('App\Photo', 'imageable');
        // return $this->hasMany('App\Photo', 'album_id', 'album_id');
    }
    /**
     * 取得該相簿的所有標籤。
     */
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }
}
