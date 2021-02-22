<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['brand_name','slug','brand_icon'];

    public function products(){
        return $this->hasMany(Products::class,'brand_id');
    }
}
