<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NairobiDropOffs extends Model
{

    protected $fillable = ['zone_id','dropoff_name'];

    public function zone(){
        return $this->belongsTo(NairobiZones::class,'zone_id');
    }
}
