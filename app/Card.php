<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{

    protected $fillable = [
        'note', 'image', 'customer_id','points'
    ];
    
    public function customer(){
        return $this->belongsTo('App\Customer');
    }
}
