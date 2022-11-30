<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Topic;
use App\TopicCategory;
use App\User;
use Faker\Generator as Faker;

$factory->define(Topic::class, function (Faker $faker) {
    return [
        'author_id' => User::all()->random()->id,
        'category_id' => TopicCategory::all()->random()->id,
        'title' => $faker->text(64),
        'body' => $faker->text(300),
    ];
});
