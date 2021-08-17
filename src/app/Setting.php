<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $guarded = [
        'id'
    ];
    public $timestamps = false;

    //setting - school
    public function school()
    {
        return $this->belongsTo('App\School', 'school_id', 'school_id');
    }
}
