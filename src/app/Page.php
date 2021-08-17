<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $primaryKey = 'page_id';
    protected $guarded = [
        'page_id',
    ];
    public function groups()
    {
        return $this->belongsToMany('App\Group', 'group_page', 'page_id', 'group_id')
        ->withPivot('show');
    }
    public function tabs()
    {
        return $this->hasMany('App\Tab','page_id','page_id');
    }
}
