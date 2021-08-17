<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat_message extends Model
{
    protected $table = 'chat_messages_new';
    protected $guarded = [
        'id',
    ];

    //user - chat_message (TEACHER)
    public function teacher()
    {
        return $this->belongsTo('App\User', 'teacher_id','id');
    }
    //user - chat_message (STUDENT)
    public function student()
    {
        return $this->belongsTo('App\User', 'user_id','id');
    }
}
