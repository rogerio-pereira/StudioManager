<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\EventTeam;
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

$factory->define(EventTeam::class, function (Faker $faker) {
    return [
        'event_id' => rand(1, 10),
        'member_id' => rand(1, 10),
    ];
});
