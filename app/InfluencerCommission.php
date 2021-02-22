<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfluencerCommission extends Model
{
    protected $fillable = ['product_id','booking_id','influencer_id','commission'];

    public function product(){
        return $this->belongsTo(Products::class,'product_id');
    }

    public function booking(){
        return $this->belongsTo(Bookings::class,'booking_id');
    }

    public function influencer(){
        return $this->belongsTo(Influencer::class,'influencer_id');
    }

}
