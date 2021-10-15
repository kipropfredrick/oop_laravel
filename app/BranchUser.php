<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchUser extends Model
{
	protected $fillable = ['user_id','branch_id','phone','slug','city_id','country','location','status','role'];


    

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
	
}
   