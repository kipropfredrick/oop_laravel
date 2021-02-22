<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
   protected $fillable = ['product_id','customer_id','booking_id','transaction_amount',
   'booking_status','date_paid'];

   public function customer(){
       return $this->belongsTo(Customers::class,'customer_id');
   }
   public function product(){
    return $this->belongsTo(Products::class,'product_id');
}

public function booking(){
    return $this->belongsTo(Bookings::class,'booking_id');
}

public function mpesapayment(){
    return $this->hasOne(Mpesapayments::class,'payment_id');
}

}
