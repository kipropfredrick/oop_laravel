<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class promotions extends Model
{
    protected $fillable = ['customers_id','booking_reference','totalpaid','discount','discounted_at'];
    //
}
