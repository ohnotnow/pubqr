<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pubqr:createadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user from ENV variables';

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
        if (! env('PUBQR_ADMIN_EMAIL')) {
            return;
        }

        $existingUser = User::where('email', '=', env('PUBQR_ADMIN_EMAIL'))->first();
        if ($existingUser) {
            $this->info('Not creating another admin with email of ' . env('PUBQR_ADMIN_EMAIL'));
            return;
        }

        $user = User::create([
            'email' => env('PUBQR_ADMIN_EMAIL'),
            'name' => env('PUBQR_ADMIN_NAME'),
            'password' => bcrypt(env('PUBQR_ADMIN_PASSWORD')),
        ]);
        $user->is_superadmin = true;
        $user->save();

        $this->info('Created new admin user ' . $user->email);
    }
}
