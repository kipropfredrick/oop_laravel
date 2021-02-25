<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategories extends Model
{
  protected $fillable = ['category_id','subcategory_name','commision'];

  public function category(){
    return $this->belongsTo(Categories::class,'category_id');
}

public function thirdlevelcategories(){
    return $this->hasMany(ThirdLevelCategory::class,'subcategory_id');
}

}
