<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfluencerCommissionTotal extends Model
{
    protected $fillable = [
        'influencer_id',
        'total_commission',
        'commission_paid',
        'pending_payment',
    ];

    public function influencer(){
        return $this->belongsTo(Influencer::class,'influencer_id');
    }
}
