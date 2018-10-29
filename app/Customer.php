<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Customer")
 * )
 */

class Customer extends Model
{

    /**
     * @OA\Property(format="string", property="username")
     * @var string
     */

    /**
     * @OA\Property(format="string", property="email")
     * @var string
     */

    /**
     * @OA\Property(format="string", property="provider")
     * @var string
     */

    /**
     * @OA\Property(format="string", property="provider_id")
     * @var string
     */

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
