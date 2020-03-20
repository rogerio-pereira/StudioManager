<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Mail\TeamEventToday;
use Illuminate\Console\Command;
use App\Mail\CustomerBillingToday;
use App\Model\Payment;
use Illuminate\Support\Facades\Mail;

class SendBillingEmailForToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sendBillingToday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email for customer for billings Today';

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
        $payments = Payment::whereDate('due_at', Carbon::today()->toDateString())
                        ->where('payed', false)
                        ->with('sale.customer')
                        ->get();

        foreach($payments as $payment) {
            Mail::to($payment->sale->customer->email)->send(new CustomerBillingToday($payment));
        }
    }
}
