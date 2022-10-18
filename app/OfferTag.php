<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferTag extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'offers_tags';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
