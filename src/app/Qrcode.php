<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{
    protected $table = 'qrcodes';
    protected $primaryKey = 'qrcode_id';
    protected $guarded = [
        'qrcode_id'
    ];
    //parent - qrcode
    public function parent()
    {
        return $this->belongsTo('App\Parents', 'parent_id', 'parent_id');
    }
}
