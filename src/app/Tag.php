<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // protected $primaryKey = 'id';
    protected $guarded = [
        'id',
    ];
    /**
     * 取得所有被賦予該標籤的相簿。
     */
    public function albums()
    {
        return $this->morphedByMany('App\Album', 'taggable');
    }
    /**
     * 取得所有被賦予該標籤的圖片。
     */
    public function photos()
    {
        return $this->morphedByMany('App\Photo', 'taggable');
    }
}
