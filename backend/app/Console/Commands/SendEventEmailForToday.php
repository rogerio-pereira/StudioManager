<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Model\Event;
use App\Mail\TeamEventToday;
use Illuminate\Console\Command;
use App\Mail\CustomerEventToday;
use Illuminate\Support\Facades\Mail;

class SendEventEmailForToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sendEventToday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email for customer and team for events Today';

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
        $events = Event::whereDate('date', Carbon::today()->toDateString())->get();

        foreach($events as $event) {
            Mail::to(env('MAIL_FORM_TO'))->send(new CustomerEventToday($event));

            foreach($event->team as $member) {
                Mail::to($member->email)->send(new TeamEventToday($event, $member));
            }
        }
    }
}
