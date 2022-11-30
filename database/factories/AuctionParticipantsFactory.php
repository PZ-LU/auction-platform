<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AuctionParticipants;
use App\User;
use App\Auction;
use Faker\Generator as Faker;

$factory->define(AuctionParticipants::class, function (Faker $faker) {
    return [
        'user_id' => User::all()->random()->id,
        'auction_id' => Auction::all()->random()->id,
        'amount' => $faker->numberBetween($min = 3, $max = 220) * 100,
    ];
});
