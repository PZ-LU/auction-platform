<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TopicCategory;
use Faker\Generator as Faker;

$factory->define(TopicCategory::class, function (Faker $faker) {
    return [
        'label' => $faker->word,
    ];
});
