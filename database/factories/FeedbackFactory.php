<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Feedback;
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

$factory->define(Feedback::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(array(1, 2, 3)),
        'sender' => $faker->firstName() . ' ' . $faker->lastName(),
        'target' => $faker->firstName() . ' ' . $faker->lastName(),
        'title' => $faker->sentence(),
        'type' => 'comment',
        'text' => $faker->text()
    ];
});
