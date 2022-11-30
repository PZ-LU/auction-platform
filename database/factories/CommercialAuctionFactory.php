<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CommercialAuction;
use Faker\Generator as Faker;

$factory->define(CommercialAuction::class, function (Faker $faker) {
    $auction = factory(App\Auction::class)->states('commercial')->create();
    $auction_finished = $auction->finished_at->format('Y-m-d H:i:s');
    return [
        'auction_id' => $auction->id,
        'start_bid' => ($faker->numberBetween($min = 3, $max = 110) * 100),
        'end_date' => $faker->dateTimeBetween($startDate = $auction_finished.' -7 days', $endDate = $auction_finished, $timezone = 'Europe/Riga'),
    ];
});
