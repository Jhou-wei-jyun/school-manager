<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionType extends Model
{
    protected $primaryKey = 'option_type_id';
    protected $table = 'option_types';
    protected $guarded = [
        'option_type_id',
    ];
    public $timestamps = false;
    public function options()
    {
        return $this->hasMany('App\Option' , 'option_type_id', 'option_type_id');
    }
}
