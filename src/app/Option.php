<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $primaryKey = 'option_id';
    protected $table = 'options';
    protected $guarded = [
        'option_id',
    ];
    //option_type - option
    public function optionType()
    {
        return $this->belongsTo('App\OptionType', 'option_type_id', 'option_type_id');
    }
    //department - option
    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id', 'id');
    }
}
