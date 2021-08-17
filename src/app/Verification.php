<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    protected $fillable = [
        'id', 'phone','verification_num','login_date','created_at','updated_at','send_flg','login_count'
    ];
}
