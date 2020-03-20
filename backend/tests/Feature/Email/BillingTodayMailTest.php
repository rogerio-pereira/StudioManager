<?php

namespace Tests\Feature\Email;

use Carbon\Carbon;
use App\Model\Sale;
use Tests\TestCase;
use App\Model\Payment;
use App\Model\Product;
use App\Model\Customer;
use App\Model\SaleProduct;
use App\Mail\CustomerBillingToday;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;


class BillingTodayMailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function sendBillingEmailForToday()
    {
        $customer = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();
        $sale = factory(Sale::class)->create([
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 0,
            'installments' => 1,
            'period' => 'Monthly',
            'start_date' => Carbon::now()->toDateString(),
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' => $product1->value,
            'due_at' => Carbon::now()->toDateString(),
            'payed' => false
        ]);

        Mail::fake();
        Mail::assertNothingSent();
        Artisan::call('email:sendBillingToday');

        Mail::assertSent(CustomerBillingToday::class, function ($mail) use ($customer) {
            return $mail->to($customer->email);
        });
    }

    /**
     * @test
     */
    public function billingEmailIsNotSendForPaymentPayed()
    {
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();
        $sale = factory(Sale::class)->create([
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 0,
            'installments' => 1,
            'period' => 'Monthly',
            'start_date' => Carbon::now()->toDateString(),
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' => $product1->value,
            'due_at' => Carbon::now()->toDateString(),
            'payed' => true
        ]);

        Mail::fake();
        Mail::assertNothingSent();
        Artisan::call('email:sendBillingToday');
        Mail::assertNothingSent();
    }

    /**
     * @test
     */
    public function billingEmailIsNotSendForPaymentOnOtherDates()
    {
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();
        $sale = factory(Sale::class)->create([
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 0,
            'installments' => 1,
            'period' => 'Monthly',
            'start_date' => Carbon::now()->addDay()->toDateString(),
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' => $product1->value,
            'due_at' => Carbon::now()->addDay()->toDateString(),
            'payed' => true
        ]);

        Mail::fake();
        Mail::assertNothingSent();
        Artisan::call('email:sendBillingToday');
        Mail::assertNothingSent();
    }
}
