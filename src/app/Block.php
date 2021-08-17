<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $primaryKey = 'block_id';
    protected $guarded = [
        'block_id',
    ];
    public function groups()
    {
        return $this->belongsToMany('App\Group', 'group_block', 'block_id', 'group_id')
        ->withPivot('show');
    }
    public function tab()
    {
        return $this->belongsTo('App\Tab', 'tab_id', 'tab_id');
    }
}
