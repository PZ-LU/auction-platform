<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AuctionObject;
use App\AuctionObjectType;
use Faker\Generator as Faker;

$factory->define(AuctionObject::class, function (Faker $faker) {
    $MEDIA_COUNT = 50;
    $dimX = $faker->numberBetween($min = 300, $max = 512);
    $dimY = $faker->numberBetween($min = 300, $max = 512);
    return [
        'object_type_id' => AuctionObjectType::all()->random()->id,
        'name' => $faker->realText(24),
        'preview_image' => 'https://picsum.photos/seed/auction_platform_image_'
                            .$faker->numberBetween($min = 0, $max = $MEDIA_COUNT)
                            .$dimX
                            .'/'
                            .$dimY,
        'body' => $faker->text(300),
    ];
});
