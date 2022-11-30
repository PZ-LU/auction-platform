<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AuctionObjectType;
use Faker\Generator as Faker;

$factory->define(AuctionObjectType::class, function (Faker $faker) {
    return [
        'label' => $faker->word,
    ];
});
