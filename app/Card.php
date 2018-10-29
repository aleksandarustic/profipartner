<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Card")
 * )
 */

class Card extends Model
{
    /**
     * @OA\Property(format="int64", property="note",example="text")
     * @var int
     */

    /**
     * @OA\Property(format="binary", property="image")
     * @var string
     */
 
    

    protected $fillable = [
        'note', 'image', 'customer_id','points'
    ];
    
    public function customer(){
        return $this->belongsTo('App\Customer');
    }
}
