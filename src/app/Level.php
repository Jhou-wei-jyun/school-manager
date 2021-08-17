<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{

    protected $table = 'levels';
    protected $guarded = [
        'id',
    ];
    public $timestamps = false;
    //level - admin
    public function admin()
    {
        return $this->belongsTo('App\Admin', 'id', 'level_id');
    }
}
