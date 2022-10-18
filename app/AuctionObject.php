<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionObject extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'auction_object';
    
    /**
    * Indicates if the model should be timestamped.
    *
    * @var bool
    */
    public $timestamps = false;
}

// Temporary
class AuctionObjectType
{
    public const CAR = 1;
}
