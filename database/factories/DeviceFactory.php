<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Device;
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

$factory->define(Device::class, function (Faker $faker) {
    $names = array('Fujitsu', 'Samsung', 'LG', 'Sony', 'Google', 'Motorola');
    $statuses = array('online', 'offline');
    $types = array('Android 10', 'Android 9', 'Android 8', 'Android 7', 'Android 6');

    return [
        'user_id' => $faker->randomElement(array(1, 2, 3)),
        'name' => $faker->randomElement($names),
        'serial_number' => $faker->ean13,
        'status' => $faker->randomElement($statuses),
        'type' => $faker->randomElement($types),
        'description' => $faker->text()
    ];
});
