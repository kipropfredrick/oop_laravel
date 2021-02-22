<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfluencerPaymentlog extends Model
{
    protected $fillable = [
        'influencer_id',
        'amount_paid'
    ];

    public function influencer(){
        return $this->belongsTo(Influencer::class,'influencer_id');
    }
}
