<?php

namespace App\Console\Commands;

use App\Customer;
use Illuminate\Console\Command;

class PurgeOldCustomerRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pubqr:purge-old-customer-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old customer records from the system';

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
     * @return int
     */
    public function handle()
    {
        Customer::where('created_at', '<=', now()->subDays(config('pubqr.retain_customer_details_days', 21)))->delete();
    }
}
