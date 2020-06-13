<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'item_id' => function () {
            return factory(Item::class)->create()->id;
        },
        'cost' => $faker->numberBetween(100, 1000),
        'contact' => $faker->word,
        'quantity' => $faker->numberBetween(1, 4),
        'is_fulfilled' => false,
        'is_paid' => false,
    ];
});
