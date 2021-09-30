<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['user_id','phone','slug','vendor_code','business_name','city_id','country','location','status','commission_rate','commission_cap','fixed_mobile_money','fixed_bank','commssionrate_enabled'];

      

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
}
