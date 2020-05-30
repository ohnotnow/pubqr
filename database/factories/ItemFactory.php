<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CodeGenerator;
use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->text(40),
        'code' => app(CodeGenerator::class)->generate($faker->numberBetween(1000, 9999)),
        'price' => $faker->numberBetween(100, 500),
        'description' => $faker->text(200),
        'is_available' => true,
    ];
});
