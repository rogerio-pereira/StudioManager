<?php

namespace Tests\Feature\Email;

use Carbon\Carbon;
use App\Model\Sale;
use App\Model\Team;
use Tests\TestCase;
use App\Model\Event;
use App\Model\Payment;
use App\Model\Product;
use App\Model\Customer;
use App\Model\EventTeam;
use App\Model\SaleProduct;
use App\Mail\TeamEventToday;
use App\Mail\CustomerEventToday;
use App\Mail\CustomerBillingToday;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;


class SendAllTodayEMailsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function sendAllEmailsForToday()
    {
        $customer = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $today = Carbon::now();
        $sale = factory(Sale::class)->create([
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 0,
            'installments' => 1,
            'period' => 'Monthly',
            'start_date' => $today->toDateString(),
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' => $product1->value,
            'due_at' => $today->toDateString(),
            'payed' => false
        ]);
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
        Artisan::call('email:sendAllToday');

        Mail::assertSent(CustomerEventToday::class, function ($mail) use ($customer) {
            return $mail->to($customer->email);
        });

        Mail::assertSent(TeamEventToday::class, function ($mail) use ($team1) {
            return $mail->to($team1->email);
        });

        Mail::assertSent(TeamEventToday::class, function ($mail) use ($team2) {
            return $mail->to($team2->email);
        });

        Mail::assertSent(CustomerBillingToday::class, function ($mail) use ($customer) {
            return $mail->to($customer->email);
        });
    }
}
