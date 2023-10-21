<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Offer;
use App\OfferTag;
use App\TagCategory;
use Faker\Generator as Faker;

$factory->define(OfferTag::class, function (Faker $faker) {
    return [
        'tag' => $faker->realText(32),
        'category_id' => TagCategory::all()->random()->id,
        'offer_id' => Offer::all()->random()->id
    ];
});
