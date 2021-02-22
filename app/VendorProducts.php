<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorProducts extends Model
{
    protected $fillable = ['category_id','vendor_id','product_name','product_code','product_price','highlights','description','product_image','reviews','status'];

     public function category(){
       return $this->belongsTo(Categories::class,'category_id');
   }
   public function vendor(){
    return $this->belongsTo(Categories::class,'vendor_id');
}
}
