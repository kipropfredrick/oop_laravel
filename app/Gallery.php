<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
  protected $fillable= ['product_id','image_path'];

  public function product(){
      return $this->belongsTo(Product::class,'product_id');
  }

}
