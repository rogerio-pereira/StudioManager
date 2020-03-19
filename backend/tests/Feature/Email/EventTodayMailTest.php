<?php

namespace Tests\Feature\Email;

use Carbon\Carbon;
use App\Model\Team;
use App\Model\User;
use Tests\TestCase;
use App\Model\Event;
use App\Model\Customer;
use App\Model\EventTeam;
use App\Mail\TeamEventToday;
use App\Mail\CustomerEventToday;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTodayMailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function sendEventEmailForTodayToCustomerAndAllTeam()
    {
        $customer = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $today = Carbon::now();

        factory(Event::class)->create([
            'customer_id' => 1,
            'date' => $today->toDateTimeString(),
            'place' => 'Place 1 Address'
        ]);
        factory(EventTeam::class)->create([
            'event_id' => 1,
            'member_id' => 1,
        ]);
        factory(EventTeam::class)->create([
            'event_id' => 1,
            'member_id' => 2,
        ]);

        Mail::fake();
        Mail::assertNothingSent();
        Artisan::call('email:sendEventToday');

        Mail::assertSent(CustomerEventToday::class, function ($mail) use ($customer) {
            return $mail->to($customer->email);
        });

        Mail::assertSent(TeamEventToday::class, function ($mail) use ($team1) {
            return $mail->to($team1->email);
        });

        Mail::assertSent(TeamEventToday::class, function ($mail) use ($team2) {
            return $mail->to($team2->email);
        });
    }
}
