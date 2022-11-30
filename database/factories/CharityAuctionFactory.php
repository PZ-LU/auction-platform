<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CharityAuction;
use App\Auction;
use Faker\Generator as Faker;

$factory->define(CharityAuction::class, function (Faker $faker) {
    return [
        'auction_id' => factory(App\Auction::class)->states('charity')->create()->id,
        'goal' => ($faker->numberBetween($min = 300, $max = 1000) * 10),
    ];
});
