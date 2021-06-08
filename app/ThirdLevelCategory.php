<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThirdLevelCategory extends Model
{
  protected $fillable = ['subcategory_id','name','slug'];

  public function subcategory(){
      return $this->belongsTo(SubCategories::class,'subcategory_id');
  }
  
}
