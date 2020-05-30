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
        $admin = factory(User::class)->create(['email' => 'admin@example.com']);
        factory(Item::class, 10)->create();
        factory(Order::class, 10)->create();
    }
}
