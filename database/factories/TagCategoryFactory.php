<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TagCategory;
use Faker\Generator as Faker;

$factory->define(TagCategory::class, function (Faker $faker) {
    return [
        'label' => substr($faker->jobTitle, 0, 32),
    ];
});
