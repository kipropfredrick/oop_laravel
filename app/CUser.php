<?php

namespace App;



use Cartalyst\Sentinel\Users\EloquentUser as CartalystUser;

class CUser extends CartalystUser {

    protected $fillable = [
      'name', 'email', 'password','mosmosid','last_login','permissions','role'
    ];

       public function customer(){
        return $this->hasOne(Customers::class, 'user_id');
    }

}