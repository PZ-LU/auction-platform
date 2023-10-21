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
    protected $table = 'auction_objects';
    
    /**
    * Indicates if the model should be timestamped.
    *
    * @var bool
    */
    public $timestamps = false;
}
