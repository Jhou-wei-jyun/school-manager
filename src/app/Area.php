<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Area extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','start_at','finish_at','area_statu_id','def_photo','num_peoples','num_devices','max_num_peoples'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_areas')
            ->withTimestamps();
    }
    public function school()
    {
        return $this->belongsTo('App\School','school_id','school_id');
    }
    public function items()
    {
        return $this->belongsToMany('App\Item', 'item_areas')
            ->withTimestamps();
    }

    public function records()
    {
        return $this->hasMany('App\Record');
    }

    public function devices()
    {
        return $this->hasMany('App\Device');
    }

    public function statu()
    {
        return $this->belongsTo('App\Statu');
    }

    public function departments()
    {
        return $this->belongsToMany('App\Department', 'department_area')
            ->withTimestamps();
    }

    public function getLocationPhotoSocial2UrlAttribute()
    {
        $storage = config('services.storage');
        if ($this->location_photo_social_2) {
            return Storage::disk($storage)->url($this->location_photo_social_2);
        }
    }

    public function getLocationPhotoSocial0UrlAttribute()
    {
        $storage = config('services.storage');
        if ($this->location_photo_social_0) {
            return Storage::disk($storage)->url($this->location_photo_social_0);
        }
    }

    public function getLocationPhotoSocial1UrlAttribute()
    {
        $storage = config('services.storage');
        if ($this->location_photo_social_1) {
            return Storage::disk($storage)->url($this->location_photo_social_1);
        }
    }

    public function getLottieUrlAttribute()
    {
        $storage = config('services.storage');
        if ($this->lottie) {
            return Storage::disk($storage)->url($this->lottie);
        }
    }

    public function getLocationEmergencyExitUrlAttribute()
    {
        $storage = config('services.storage');
        if ($this->location_emergency_exit) {
            return Storage::disk($storage)->url($this->location_emergency_exit);
        }
    }

}
