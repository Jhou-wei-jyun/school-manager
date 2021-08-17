<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tab extends Model
{
    protected $primaryKey = 'tab_id';
    protected $guarded = [
        'tab_id',
    ];
    public function groups()
    {
        return $this->belongsToMany('App\Group', 'group_tab', 'tab_id', 'group_id')
        ->withPivot('show');
    }
    public function blocks()
    {
        return $this->hasMany('App\Block','tab_id','tab_id');
    }
    public function page()
    {
        return $this->belongsTo('App\Page', 'page_id', 'page_id');
    }
}
