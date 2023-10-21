<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TopicComment;
use App\Topic;
use App\User;
use Faker\Generator as Faker;

$factory->define(TopicComment::class, function (Faker $faker) {
    return [
        'user_id' => User::all()->random()->id,
        'topic_id' => Topic::all()->random()->id,
        'body' => $faker->text(300),
    ];
});
