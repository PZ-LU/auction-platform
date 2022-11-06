<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionObjectType extends Model
{
    protected $table = 'object_type';
    public $timestamps = false;

    public const CAR = 1;
}
