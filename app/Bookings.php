<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
  protected $fillable = ['product_id','county_id','location_id','customer_id','payment_mode','date_started','due_date','total_cost','setdate','setreminder'];

  public function product(){
      return $this->belongsTo(Products::class,'product_id');
  }
  public function customer(){
    return $this->belongsTo(Customers::class,'customer_id');
}
public function county(){
  return $this->belongsTo(Counties::class,'county_id');
}
public function location(){
  return $this->belongsTo(PickupLocation::class,'location_id');
}

public function zone(){
  return $this->belongsTo(NairobiZones::class,'zone_id');
}

public function payments(){
  return $this->hasMany(Payments::class,'booking_id');
}

public function dropoff(){
  return $this->belongsTo(NairobiDropOffs::class,'dropoff_id');
}
public function vendor(){
  return $this->belongsTo(Vendor::class,'vendor_code','vendor_code');
}
}
