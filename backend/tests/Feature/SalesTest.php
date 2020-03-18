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

class SalesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aUserCanGetAllSales()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $customer2 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();

        factory(Sale::class)->create([
            'customer_id' => 1,
            'value' => 200,
            'discount' => 50,
            'installments' => 1,
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' => 150,
            'due_at' => Carbon::now()->addMonth()->toDateString(),
            'payed' => false
        ]);



        factory(Sale::class)->create([
            'customer_id' => 2,
            'value' => 500,
            'discount' => 0,
            'installments' => 2,
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 2,
            'product_id' => 1,
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 2,
            'product_id' => 2,
        ]);
        factory(Payment::class)->create([
            'sale_id' => 2,
            'amount' => 250,
            'due_at' => Carbon::now()->addMonth()->toDateString(),
            'payed' => false
        ]);
        factory(Payment::class)->create([
            'sale_id' => 2,
            'amount' => 250,
            'due_at' => Carbon::now()->addMonth(2)->toDateString(),
            'payed' => false
        ]);

        $response = $this->get('/api/sales');
        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 1,
                    'value' => 200,
                    'discount' => 50,
                    'installments' => 1,
                    'customer' => [
                        'id' => 1,
                        'name' => $customer1->name
                    ],
                    'products' => [
                        [
                            'id' => 1,
                            'name' => $product1->name
                        ]
                    ],
                    'payments' => [
                        [
                            'sale_id' => 1,
                            'amount' => 150,
                            'due_at' => Carbon::now()->addMonth()->toDateString(),
                            'payed' => false
                        ]
                    ]
                ],
                [
                    'id' => 2,
                    'value' => 500,
                    'discount' => 0,
                    'installments' => 2,
                    'customer' => [
                        'id' => 2,
                        'name' => $customer2->name
                    ],
                    'products' => [
                        [
                            'id' => 1,
                            'name' => $product1->name
                        ],
                        [
                            'id' => 2,
                            'name' => $product2->name
                        ]
                    ],
                    'payments' => [
                        [
                            'sale_id' => 2,
                            'amount' => 250,
                            'due_at' => Carbon::now()->addMonth()->toDateString(),
                            'payed' => false
                        ],

                        [
                            'sale_id' => 2,
                            'amount' => 250,
                            'due_at' => Carbon::now()->addMonth(2)->toDateString(),
                            'payed' => false
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanGetASales()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();

        factory(Sale::class)->create([
            'customer_id' => 1,
            'value' => 300,
            'discount' => 50,
            'installments' => 2,
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 2,
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' => 175,
            'due_at' => Carbon::now()->addMonth()->toDateString(),
            'payed' => false
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' => 175,
            'due_at' => Carbon::now()->addMonth(2)->toDateString(),
            'payed' => false
        ]);

        $response = $this->get('/api/sales/1');
        $response->assertOk()
            ->assertJson([
                'id' => 1,
                'value' => 300,
                'discount' => 50,
                'installments' => 2,
                'customer' => [
                    'id' => 1,
                    'name' => $customer1->name
                ],
                'products' => [
                    [
                        'id' => 1,
                        'name' => $product1->name
                    ],
                    [
                        'id' => 2,
                        'name' => $product2->name
                    ]
                ],
                'payments' => [
                    [
                        'sale_id' => 1,
                        'amount' => 175,
                        'due_at' => Carbon::now()->addMonth()->toDateString(),
                        'payed' => false
                    ],
                    [
                        'sale_id' => 1,
                        'amount' => 175,
                        'due_at' => Carbon::now()->addMonth(2)->toDateString(),
                        'payed' => false
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateASale()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();
        $total = $product1->value + $product2->value;
        $installmentValue = ($total-50)/2;

        $data = [
            'customer_id' => 1,
            'value' => $total,
            'discount' => 50,
            'installments' => 2,
            'products' => [1, 2],
            'period' => 'Weekly',
            'start_date' => Carbon::now()->addWeek()->toDateString(),
        ];

        $request = $this->post('/api/sales', $data);
        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'value' => $total,
                'discount' => 50,
                'installments' => 2,
                'customer' => [
                    'id' => 1,
                    'name' => $customer1->name
                ],
                'products' => [
                    [
                        'id' => 1,
                        'name' => $product1->name
                    ],
                    [
                        'id' => 2,
                        'name' => $product2->name
                    ]
                ],
                'payments' => [
                    [
                        'sale_id' => 1,
                        'amount' => $installmentValue,
                        'due_at' => Carbon::now()->addWeek()->toDateString(),
                        'payed' => false
                    ],
                    [
                        'sale_id' => 1,
                        'amount' => $installmentValue,
                        'due_at' => Carbon::now()->addWeek(2)->toDateString(),
                        'payed' => false
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateASaleWithoutRequiredFields()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();

        $data = [
            'customer_id' => '',
            'value' => '',
            'discount' => '',
            'installments' => '',
            'products' => '',
            'period' => '',
            'start_date' => '',
        ];

        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'customer_id' => [
                        'The customer id field is required.'
                    ],
                    'value' => [
                        'The value field is required.'
                    ],
                    'discount' => [
                        'The discount field is required.'
                    ],
                    'installments' => [
                        'The installments field is required.'
                    ],
                    'products' => [
                        'The products field is required.'
                    ],
                    'period' => [
                        'The period field is required.'
                    ],
                    'start_date' => [
                        'The start date field is required.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateASaleWithInvalidCustomerIdField()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();

        $data = [
            'customer_id' => 'customer',
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 2,
            'products' => [1],
            'period' => 'Weekly',
            'start_date' => Carbon::now()->addWeek()->toDateString(),
        ];

        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'customer_id' => [
                        'The customer id must be a number.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateASaleWithWrongCustomerIdField()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();

        $data = [
            'customer_id' => 2,
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 2,
            'products' => [1],
            'period' => 'Weekly',
            'start_date' => Carbon::now()->addWeek()->toDateString(),
        ];

        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'customer_id' => [
                        'The selected customer id is invalid.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateASaleWithWrongNumericField()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();

        $data = [
            'customer_id' => 1,
            'value' => 'value',
            'discount' => 'discount',
            'installments' => 'installments',
            'products' => [1],
            'period' => 'Weekly',
            'start_date' => Carbon::now()->addWeek()->toDateString(),
        ];

        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'value' => [
                        'The value must be a number.'
                    ],
                    'discount' => [
                        'The discount must be a number.'
                    ],
                    'installments' => [
                        'The installments must be a number.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateASaleWithInvalidStartDateField()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();

        $data = [
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 2,
            'products' => [1],
            'period' => 'Weekly',
            'start_date' => 'date',
        ];

        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'start_date' => [
                        'The start date is not a valid date.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateASaleWithDateFieldInThePast()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();

        $data = [
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 2,
            'products' => [1],
            'period' => 'Weekly',
            'start_date' => Carbon::now()->subDay()->toDateString(),
        ];

        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'start_date' => [
                        'The start date must be a date after today.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateASaleWithInvalidProduct()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();

        $data = [
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 2,
            'products' => 'products',
            'period' => 'Weekly',
            'start_date' => Carbon::now()->addDay()->toDateString(),
        ];

        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'products' => [
                        'The products must be an array.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateASaleWithWrongProduct()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();

        $data = [
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 2,
            'products' => [1, 2],
            'period' => 'Weekly',
            'start_date' => Carbon::now()->addDay()->toDateString(),
        ];

        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'products' => [
                        'The selected products is invalid.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateASaleWithWrongPeriod()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();

        $data = [
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 2,
            'products' => [1],
            'period' => 'Daily',
            'start_date' => Carbon::now()->addDay()->toDateString(),
        ];

        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'period' => [
                        'The selected period is invalid.'
                    ],
                ]
            ]);
        
        $data['period'] = 'Quarterly';
        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'period' => [
                        'The selected period is invalid.'
                    ],
                ]
            ]);
    
        $data['period'] = 'Semiannually';
        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'period' => [
                        'The selected period is invalid.'
                    ],
                ]
            ]);

        $data['period'] = 'Annually';
        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'period' => [
                        'The selected period is invalid.'
                    ],
                ]
            ]);

        $data['period'] = 'Weekly';
        $request = $this->post('/api/sales', $data);
        $request->assertCreated();

        $data['period'] = 'Biweekly';
        $request = $this->post('/api/sales', $data);
        $request->assertCreated();

        $data['period'] = 'Monthly';
        $request = $this->post('/api/sales', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCantCreateASaleWithDateFieldToday()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();

        $data = [
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 2,
            'products' => [1],
            'period' => 'Weekly',
            'start_date' => Carbon::now()->toDateString(),
        ];

        $request = $this->post('/api/sales', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'start_date' => [
                        'The start date must be a date after today.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanUpdateASale()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $customer2 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();
        $sale = factory(Sale::class)->create([
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 1,
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' => $product1->value - 50,
            'due_at' => Carbon::now()->addMonth()->toDateString(),
            'payed' => false
        ]);

        $response = $this->get('/api/sales');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'value' => $sale->value,
                    'discount' => $sale->discount,
                    'installments' => 1,
                    'customer' => [
                        'id' => 1,
                        'name' => $customer1->name
                    ],
                    'products' => [
                        [
                            'id' => 1,
                            'name' => $product1->name
                        ]
                    ],
                    'payments' => [
                        [
                            'sale_id' => 1,
                            'amount' => $product1->value - 50,
                            'due_at' => Carbon::now()->addMonth()->toDateString(),
                            'payed' => false
                        ]
                    ]
                ]
            ]);


        $total = $product1->value + $product2->value;
        $installmentValue = ($total-30)/2;
        $data = [
            'customer_id' => 2,
            'value' => $total,
            'discount' => 30,
            'installments' => 2,
            'products' => [1, 2],
            'period' => 'Biweekly',
            'start_date' => Carbon::now()->addWeek(2)->toDateString(),
        ];

        $request = $this->put('/api/sales/1', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'value' => $total,
                'discount' => 30,
                'installments' => 2,
                'customer' => [
                    'id' => 2,
                    'name' => $customer2->name
                ],
                'products' => [
                    [
                        'id' => 1,
                        'name' => $product1->name
                    ],
                    [
                        'id' => 2,
                        'name' => $product2->name
                    ]
                ],
                'payments' => [
                    [
                        'sale_id' => 1,
                        'amount' => $installmentValue,
                        'due_at' => Carbon::now()->addWeek(2)->toDateString(),
                        'payed' => false
                    ],
                    [
                        'sale_id' => 1,
                        'amount' => $installmentValue,
                        'due_at' => Carbon::now()->addWeek(4)->toDateString(),
                        'payed' => false
                    ]
                ]
            ]);

            $response = $this->get('/api/sales');
            $response->assertOk()
                ->assertJsonCount(1)
                ->assertJson([
                    [
                        'id' => 1,
                        'value' => $total,
                        'discount' => 30,
                        'installments' => 2,
                        'customer' => [
                            'id' => 2,
                            'name' => $customer2->name
                        ],
                        'products' => [
                            [
                                'id' => 1,
                                'name' => $product1->name
                            ],
                            [
                                'id' => 2,
                                'name' => $product2->name
                            ]
                        ],
                        'payments' => [
                            [
                                'sale_id' => 1,
                                'amount' => $installmentValue,
                                'due_at' => Carbon::now()->addWeek(2)->toDateString(),
                                'payed' => false
                            ],
                            [
                                'sale_id' => 1,
                                'amount' => $installmentValue,
                                'due_at' => Carbon::now()->addWeek(4)->toDateString(),
                                'payed' => false
                            ]
                        ]
                    ]
                ]);
    }

    /**
     * @test
     */
    public function aUserCanDeleteASale()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $product1 = factory(Product::class)->create();
        $sale = factory(Sale::class)->create([
            'customer_id' => 1,
            'value' => $product1->value,
            'discount' => 50,
            'installments' => 1,
        ]);
        factory(SaleProduct::class)->create([
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        factory(Payment::class)->create([
            'sale_id' => 1,
            'amount' => $product1->value - 50,
            'due_at' => Carbon::now()->addMonth()->toDateString(),
            'payed' => false
        ]);

        $response = $this->get('/api/sales');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'value' => $sale->value,
                    'discount' => $sale->discount,
                    'installments' => 1,
                    'customer' => [
                        'id' => 1,
                        'name' => $customer1->name
                    ],
                    'products' => [
                        [
                            'id' => 1,
                            'name' => $product1->name
                        ]
                    ],
                    'payments' => [
                        [
                            'sale_id' => 1,
                            'amount' => $product1->value - 50,
                            'due_at' => Carbon::now()->addMonth()->toDateString(),
                            'payed' => false
                        ]
                    ]
                ]
            ]);

        $request = $this->delete('/api/sales/1');
        $request->assertOk()
            ->assertJson([]);
        
        $this->assertDatabaseMissing('sale_products', [
            'sale_id' => 1,
            'product_id' => 1,
        ]);
        $this->assertDatabaseMissing('payments', [
            'sale_id' => 1,
            'amount' => $product1->value - 50,
            'due_at' => Carbon::now()->addMonth()->toDateString(),
            'payed' => false
        ]);

        $response = $this->get('/api/sales');
        $response->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }
}
