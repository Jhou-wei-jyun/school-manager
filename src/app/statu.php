<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statu extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    public function records()
    {
        return $this->hasMany('App\Record');
    }

    // public function devices()
    // {
    //     return $this->hasMany('App\department');
    // }

    public function areas()
    {
        return $this->hasMany('App\Area');
    }
}
