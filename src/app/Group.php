<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $primaryKey = 'group_id';
    protected $guarded = [
        'group_id',
    ];
    public $timestamps = false;

    public function admin()
    {
        return $this->hasOne('App\Admin', 'group_id', 'group_id');
    }
    public function pages()
    {
        return $this->belongsToMany('App\Page', 'group_page', 'group_id', 'page_id')
        ->withPivot('show');
    }
    public function tabs()
    {
        return $this->belongsToMany('App\Tab', 'group_tab', 'group_id', 'tab_id')
        ->withPivot('show');
    }
    public function blocks()
    {
        return $this->belongsToMany('App\Block', 'group_block', 'group_id', 'block_id')
        ->withPivot('show');
    }
}
