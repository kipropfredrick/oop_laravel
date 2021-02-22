<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PickupLocation extends Model
{
    protected $fillable = ['county_id','town','center_name','street','building','direction_tip','contact_no'];

    public function county(){
        return $this->belongsTo(Counties::class,'county_id');
    }

}
