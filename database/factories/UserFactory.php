<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(User::class, function (Faker $faker) {
    static $password;

    return [
        'email' => 'admin@tirpitz.com',//$faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'activation_token' => Str::uuid(),
        'remember_token' => '',
        'status' => 'activated'
    ];
});
