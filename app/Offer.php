<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'user_id', 'title', 'body'
    ];

    public $timestamps = true;
}
