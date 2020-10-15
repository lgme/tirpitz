<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TimeOff;
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

$factory->define(TimeOff::class, function (Faker $faker) {
    $statuses = array('sick leave', 'unpaid leave', 'vacation', 'funeral', 'wedding');
    return [
        'user_id' => $faker->randomElement(array(1, 2, 3)),
        'status' => $faker->randomElement($statuses),
        'request_type' => 1,
        'start_date' => $faker->dateTimeBetween('+0 days', '+1 week'),
        'end_date' => $faker->dateTimeBetween('+1 month', '+1 year'),
    ];
});
