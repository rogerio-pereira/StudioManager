<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Sale;
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

$factory->define(Sale::class, function (Faker $faker) {
    return [
        'customer_id' => rand(1, 10),
        'value' => rand(500, 1000),
        'discount' => rand(100, 200),
        'installments' => 1,
    ];
});
