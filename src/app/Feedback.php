<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'feedbacks';

    protected $guarded = [
        'id',
    ];
    public function userable()
    {
        return $this->morphTo();
    }
    public function type()
    {
        return $this->belongsTo('App\FeedbackType', 'feedback_type_id_id', 'id');
    }
}
