<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NairobiZones extends Model
{
    protected $fillable = ['zone_name','price_one_way','price_return'];

    public function dropoffs(){
        return $this->hasMany(NairobiDropOffs::class,'zone_id');
    }
}
