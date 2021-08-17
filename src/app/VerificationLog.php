<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerificationLog extends Model
{
    protected $table = 'verification_log';
    protected $guarded = [
        'id'
    ];
}
