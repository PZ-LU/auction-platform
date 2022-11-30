<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Auction;
use App\AuctionObject;
use Faker\Generator as Faker;

$factory->define(Auction::class, function (Faker $faker) {
    $started_at = $faker->dateTimeBetween($startDate = '-1 year', $endDate = 'now', $timezone = 'Europe/Riga');
    do {
        $finished_at = $faker->dateTimeBetween($startDate = '-1 year', $endDate = 'now', $timezone = 'Europe/Riga');
    } while($started_at > $finished_at);
    return [
        'object_id' => factory(App\AuctionObject::class)->create()->id,
        'started_at' => $started_at,
        'finished_at' => $finished_at,
        'status' => 'dismissed',
        'type' => $faker->randomElement(['charity', 'commercial']),
    ];
});

$factory->state(App\Auction::class, 'charity', function (Faker $faker) {
    return [
        'type' => 'charity',
    ];
});

$factory->state(App\Auction::class, 'commercial', function (Faker $faker) {
    return [
        'type' => 'commercial',
    ];
});
