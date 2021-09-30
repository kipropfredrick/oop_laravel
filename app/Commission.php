<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = ['product_id','booking_id','vendor_id','agent_id','admin_commission','other_party_commission'];

    public function product(){

        return $this->belongsTo(Products::class,'product_id');

    }

    public function booking(){

        return $this->belongsTo(Bookings::class,'booking_id');

    }

    public function vendor(){

        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function agent(){

        return $this->belongsTo(Agents::class,'agent_id');

    }
    
}
