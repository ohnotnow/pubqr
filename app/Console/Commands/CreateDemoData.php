<?php

namespace App\Console\Commands;

use App\CodeGenerator;
use App\Item;
use App\Order;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pubqr:createdemodata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates demo data for bringing up a demo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (User::count() > 0) {
            $this->info('Not creating a demo admin as there are already users in the db');
            return;
        }
        $admin = new User;
        $admin->name = 'Admin';
        $admin->email = 'admin@example.com';
        $admin->password = bcrypt('password');
        $admin->is_superadmin = true;
        $admin->can_login = true;
        $admin->save();
        $this->info('Created demo admin admin@example.com / password');
        foreach (range(1, 5) as $count) {
            $item = Item::create([
                'name' => 'item ' . $count,
                'description' => 'description ' . $count,
                'price' => rand(2, 7) * 100,
            ]);
            $item->code = app(CodeGenerator::class)->generate($item->id);
            $item->save();
        }
        Item::get()->each(function ($item) {
            foreach (range(1, 3) as $count) {
                Order::create([
                    'item_id' => $item->id,
                    'contact' => 'jenny smith',
                    'quantity' => rand(1, 3),
                ]);
            }
        });
    }
}
