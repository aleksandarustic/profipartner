<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{

    protected $fillable = [
        'desc', 'image', 'user_id'
    ];
    
    public function user(){
        return $this->belongsTo('App\User');
    }
}
