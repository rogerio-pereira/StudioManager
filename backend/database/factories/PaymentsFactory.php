<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Payment;
use Carbon\Carbon;
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

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'sale_id' => rand(1, 10),
        'due_at' => $faker->date($format = 'Y-m-d', $max = '+1 year'),
        'amount' => rand(100, 1000),
        'payed' => rand(0,1),
    ];
});
