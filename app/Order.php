<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Order")
 * )
 */

class Order extends Model
{

     /**
     * @OA\Property(format="string", property="reward_id")
     * @var string
     */


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
