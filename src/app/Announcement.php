<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'id','title','filename','avatar','user_id','school_id','is_show'
    ];

    public function admin()
    {
        return $this->belongsTo('App\Admin', 'admin_id' ,'id');
    }
}
