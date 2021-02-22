<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['user_id','phone','business_name','city_id','country','location','status'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
}
