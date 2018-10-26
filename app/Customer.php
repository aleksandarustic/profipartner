<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'username', 'email','provider','provider_id','points','api_token'
    ];

    public function cards(){
        return $this->hasMany('App\Card');
    }

    public function orders(){
        return $this->hasMany('App\Order');
    }
}
