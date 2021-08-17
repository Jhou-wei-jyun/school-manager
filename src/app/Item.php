<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'level_id','name','mac','active','photo','active','updated_at','created_at','details'
    ];


    public static $registerItemRule = [
        // 'name' => 'required|string',
        'mac' => 'required|string|unique:items,mac',
    ];

    public function getPhotoUrlAttribute()
    {
        $storage = config('services.storage');
        if ($this->photo) {
            return Storage::disk($storage)->url($this->photo);
        }
    }

    public function level()
    {
        return $this->belongTo('App\Level');
    }

    public function areas()
    {
        return $this->belongsToMany('App\Area', 'item_areas')
        ->withTimestamps();
    }

    public function records()
    {
        return $this->hasMany('App\Record');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'item_categories')->withTimestamps();
    }

}
