<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name','active','updated_at','created_at'
    ];

    public function items()
    {
        return $this->belongsToMany('App\Item', 'item_categories')->withTimestamps();
    }
}
