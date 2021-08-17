<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackType extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'feedback_types';

    protected $guarded = [
        'id',
    ];

    public function feedbacks()
    {
        return $this->hasMany('App\Feedback', 'feedback_type_id_id', 'id');
    }
}
