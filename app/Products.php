<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
   protected $fillable = ['category_id','third_level_category_id','agent_id','vendor_id','clicks','brand_id','status','subcategory_id','product_name','product_code','product_price','weight','highlights','description','product_image','reviews'];

   public function category(){
       return $this->belongsTo(Categories::class,'category_id');
   }

   public function subcategory(){
    return $this->belongsTo(SubCategories::class,'subcategory_id');
}


public function third_level_category(){
    return $this->belongsTo(ThirdLevelCategory::class,'third_level_category_id');
}


   public function agent(){
    return $this->belongsTo(Agents::class,'agent_id');
}

public function vendor(){
    return $this->belongsTo(Vendor::class,'vendor_id');
}

public function gallery(){
    return $this->hasMany(Gallery::class,'product_id');
}

public function brand(){
    return $this->belongsTo(Brand::class,'brand_id');   
}

}
