<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
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

$factory->define(Project::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(array(1, 2, 3)),
        'name' => $faker->catchPhrase,
        'client_name' => $faker->firstName() . ' ' . $faker->lastName(),
        'start_date' => $faker->dateTimeBetween('+0 days', '+1 week'),
        'end_date' => $faker->dateTimeBetween('+1 month', '+1 year'),
        'description' => $faker->text()
    ];
});
