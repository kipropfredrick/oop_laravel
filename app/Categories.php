<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $fillable = ['category_name','category_icon','slug'];

    public function subcategories(){
        return $this->hasMany(SubCategories::class,'category_id');
    }

    public function products(){
        return $this->hasMany(Products::class,'category_id');
    }
}
