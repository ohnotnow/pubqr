<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'contact_number' => encrypt('07' . $faker->randomNumber(8)),
        'contact_name' => encrypt($faker->name()),
    ];
});
