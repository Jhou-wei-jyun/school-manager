<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotUserVerification extends Model
{
    protected $table = 'not_user_verifications';
    protected $guarded = [
        'id',
    ];
}
