<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class topups extends Model
{
	

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'sender', 'receiver','transid','amount','description','balance','token','status'
    ];

    //
}
