<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $primaryKey = 'photo_id';
    protected $guarded = [
        'photo_id',
    ];
    // //photo - medicine
    // public function medicine()
    // {
    //     return $this->belongsTo('App\User', 'medicine_id', 'id');
    // }
    // //photo - album
    // public function album()
    // {
    //     return $this->belongsTo('App\Album', 'album_id', 'album_id');
    // }
    public function imageable()
    {
        return $this->morphTo();
    }
    /**
     * 取得該圖片的所有人物標籤。
     */
    public function userTags()
    {
        return $this->morphToMany('App\User', 'user_taggable');
    }
    /**
     * 取得該圖片的所有類型標籤。
     */
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }
}
