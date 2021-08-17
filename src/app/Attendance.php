<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
