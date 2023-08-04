<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;


/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->firstname,
        'surname' => $faker->lastname,
        'username' => substr($faker->userName, 0, 16),
        'email' => $faker->unique()->safeEmail,
        'gender' => $faker->randomElement(['male', 'female']),
        'avatar_path' => URL::to('/') . '/storage/resources/default.svg',
        'password' => bcrypt('12345678'),
        'role' => 'user',
        'status' => $faker->randomElement(['active', 'suspended'])
    ];
});

$factory->state(App\User::class, 'test_user', function (Faker $faker) {
    return [
        'username' => 'test_user',
        'password' => bcrypt('secret'),
        'role' => 'user',
        'status' => 'active'
    ];
});

$factory->state(App\User::class, 'test_admin', function (Faker $faker) {
    return [
        'username' => 'test_admin',
        'password' => bcrypt('secret'),
        'role' => 'admin',
        'status' => 'active'
    ];
});

$factory->state(App\User::class, 'admin', function (Faker $faker) {
    return [
        'password' => bcrypt('secret'),
        'role' => 'admin',
        'status' => 'active'
    ];
});

$factory->state(App\User::class, 'super_admin', function (Faker $faker) {
    return [
        'username' => 'mainAdmin',
        'password' => bcrypt('secret'),
        'role' => 'super_admin',
        'status' => 'active'
    ];
});