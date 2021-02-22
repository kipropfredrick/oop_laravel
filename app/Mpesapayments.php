<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mpesapayments extends Model
{
    protected $fillable = ['phone','payment_id','transac_code','amount_paid','date_paid',
    'created_at','updated_at'];

    public function payment(){
        return $this->belongsTo(Payments::class,'payment_id');
    }

}
