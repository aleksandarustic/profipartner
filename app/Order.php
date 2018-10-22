<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id', 'reward_id','points'
    ];

    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function reward(){
        return $this->belongsTo('App\Reward');
    }
}
