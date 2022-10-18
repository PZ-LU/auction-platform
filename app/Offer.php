<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'author_id', 'title', 'body'
    ];

    public $timestamps = true;
}

namespace App\Offer;

class Status
{
    public const ACTIVE = 'active';
    public const ARCHIVED = 'archived';
}