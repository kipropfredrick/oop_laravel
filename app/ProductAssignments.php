<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAssignments extends Model
{
   protected $fillable = ['product_id','agent_id','quantity'];

   public function product(){
       return $this->belongsTo(Products::class,'product_id');
   }

   public function agent(){
    return $this->belongsTo(Agents::class,'agent_id');
  }

}
