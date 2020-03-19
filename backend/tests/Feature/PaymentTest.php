<?php

namespace Tests\Feature;

use Carbon\Carbon;
use App\Model\Sale;
use App\Model\User;
use Tests\TestCase;
use App\Model\Payment;
use App\Model\Product;
use App\Model\Customer;
use App\Model\SaleProduct;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aUserCanGetAllPayments()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();
        $product3 = factory(Product::class)->create();
        $total = $product1->value + $product2->value + $product3->value;
        $installmentValue = ($total - 50) / 3;
        $installmentValue = round($installmentValue, 2);

        $sale = factory(Sale::class)->create([
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 3,
            'period' => 'Weekly',
            'start_date' => Carbon::now()->toDateString(),
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 2,
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 3,
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' =>  $installmentValue,
            'due_at' => Carbon::now()->toDateString(),
            'payed' => true
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' =>  $installmentValue,
            'due_at' => Carbon::now()->addWeek()->toDateString(),
            'payed' => false
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' =>  $installmentValue,
            'due_at' => Carbon::now()->addWeek(2)->toDateString(),
            'payed' => false
        ]);

        $response = $this->get('/api/payments/1');
        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'id' => 1,
                    'sale_id' => 1,
                    'amount' => $installmentValue,
                    'due_at' => Carbon::now()->toDateString(),
                    'payed' => true
                ],
                [
                    'id' => 2,
                    'sale_id' => 1,
                    'amount' => $installmentValue,
                    'due_at' => Carbon::now()->addWeek()->toDateString(),
                    'payed' => false
                ],
                [
                    'id' => 3,
                    'sale_id' => 1,
                    'amount' => $installmentValue,
                    'due_at' => Carbon::now()->addWeek(2)->toDateString(),
                    'payed' => false
                ]
            ]);
    }

    /**
     * @test
     */
    public function aSaleWithoutPaymentShouldReturnEmptyArray()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();
        $product3 = factory(Product::class)->create();
        $total = $product1->value + $product2->value + $product3->value;
        $installmentValue = ($total - 50) / 3;

        $sale = factory(Sale::class)->create([
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 3,
            'period' => 'Weekly',
            'start_date' => Carbon::now()->toDateString(),
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 2,
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 3,
        ]);

        $response = $this->get('/api/payments/1');
        $response->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }



    /**
     * @test
     */
    public function AUserCanChangePaymentToPayed()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();

        $sale = factory(Sale::class)->create([
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 0,
            'installments' => 2,
            'period' => 'Weekly',
            'start_date' => Carbon::now()->toDateString(),
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' =>  $product1->value/2,
            'due_at' => Carbon::now()->toDateString(),
            'payed' => false
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' =>  $product1->value/2,
            'due_at' => Carbon::now()->addWeek()->toDateString(),
            'payed' => false
        ]);

        //GET
        $response = $this->get('/api/payments/1');
        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 1,
                    'sale_id' => 1,
                    'amount' => $product1->value / 2,
                    'due_at' => Carbon::now()->toDateString(),
                    'payed' => false
                ],
                [
                    'id' => 2,
                    'sale_id' => 1,
                    'amount' => $product1->value / 2,
                    'due_at' => Carbon::now()->addWeek()->toDateString(),
                    'payed' => false
                ],
            ]);

        //PUT
        $request = $this->put('/api/payment/1/pay', ['payed' => true]);
        $request->assertOk();

        //GET
        $response = $this->get('/api/payments/1');
        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 1,
                    'sale_id' => 1,
                    'amount' => $product1->value / 2,
                    'due_at' => Carbon::now()->toDateString(),
                    'payed' => true
                ],
                [
                    'id' => 2,
                    'sale_id' => 1,
                    'amount' => $product1->value / 2,
                    'due_at' => Carbon::now()->addWeek()->toDateString(),
                    'payed' => false
                ],
            ]);
    }
}
