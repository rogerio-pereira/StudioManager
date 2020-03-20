<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Model\Payment;
use App\Mail\TeamEventToday;
use Illuminate\Console\Command;
use App\Mail\CustomerBillingToday;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

class SendAllEmailForToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sendAllToday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all emails for today';

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
        Artisan::call('email:sendBillingToday');
        Artisan::call('email:sendEventToday');
    }
}
