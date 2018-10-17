<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'username', 'email','provider','provider_id','points'
    ];

    public function receipts(){
        return $this->hasMany('App\Receipt');
    }
}
