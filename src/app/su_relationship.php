<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class su_relationship extends Model
{
    protected $table = 'su_relationships';
    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function school()
    {
        return $this->belongsTo('App\School', 'school_id');
    }
}
