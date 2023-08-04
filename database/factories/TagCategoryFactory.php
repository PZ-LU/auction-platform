<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TagCategory;
use Faker\Generator as Faker;

$factory->define(TagCategory::class, function (Faker $faker) {
    return [
        'label' => substr($faker->jobTitle, 0, 32),
    ];
});

$factory->state(TagCategory::class, 'test_tag_category', function (Faker $faker) {
    return [
        'label' => 'test_tag_category',
    ];
});
