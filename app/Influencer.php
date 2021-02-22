<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Influencer extends Model
{
    protected $fillable = ['user_id',
    'phone',
    'store_name',
    'commission',
    'code'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function commission_totals(){
        return $this->hasOne(InfluencerCommissionTotal::class,'influencer_id');
    }
}
