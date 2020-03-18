<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Event;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

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

$factory->define(Event::class, function (Faker $faker) {
    return [
        'customer_id' => rand(1, 10),
        'date' => $faker->dateTimeBetween('-5 days', '+5 days', null),
        'place' => $faker->words(3, true)
    ];
});
