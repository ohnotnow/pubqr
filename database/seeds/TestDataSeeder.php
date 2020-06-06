<?php

use App\Item;
use App\Order;
use App\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->states('superadmin')->create(['email' => 'admin@example.com']);
        foreach (range(1, 5) as $count) {
            factory(User::class)->create(['email' => "staff{$count}@example.com"]);
        }
        factory(Item::class, 10)->create();
        factory(Order::class, 10)->create();
    }
}
