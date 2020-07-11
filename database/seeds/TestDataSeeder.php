<?php

use App\Item;
use App\Order;
use App\User;
use App\Customer;
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
        foreach (range(1, 10) as $day) {
            factory(Customer::class, rand(10, 100))->create(["created_at" => now()->subDays($day)]);
        }
    }
}
