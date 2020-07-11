<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'contact_number' => encrypt($faker->randomNumber(6)),
        'contact_name' => encrypt($faker->name()),
    ];
});
