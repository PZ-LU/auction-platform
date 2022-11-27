<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\User;
use Faker\Generator as Faker;

$factory->define(App\Offer::class, function (Faker $faker) {
    return [
        'author_id' => User::all()->random()->id,
        'title' => $faker->text(40),
        'body' => $faker->text(300),
        'contact_phone' => $faker->numberBetween($min = 1000000, $max = 999999999999),
        'status' => $faker->randomElement(['active', 'archived']),
    ];
});
