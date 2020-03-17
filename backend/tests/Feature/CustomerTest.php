<?php

namespace Tests\Feature;

use App\Model\Customer;
use App\Model\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aUserCanGetAllCustomerSortedByNameAsc()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Customer::class)->create([
            'name' => 'Customer Name 2',
            'email' => 'customer2@customer.com',
            'phone' => '(888) 888-8888',
            'address' => '888 Customer St',
            'address2' => 'Apt 8',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '88888',
        ]);
        factory(Customer::class)->create([
            'name' => 'Customer Name 1',
            'email' => 'customer1@customer.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Customer St',
            'address2' => 'Apt 9',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '99999',
        ]);

        $response = $this->get('/api/customers');
        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 2,
                    'name' => 'Customer Name 1',
                    'email' => 'customer1@customer.com',
                    'phone' => '(999) 999-9999',
                    'address' => '999 Customer St',
                    'address2' => 'Apt 9',
                    'city' => 'City',
                    'state' => 'ST',
                    'zipcode' => '99999',
                ],
                [
                    'id' => 1,
                    'name' => 'Customer Name 2',
                    'email' => 'customer2@customer.com',
                    'phone' => '(888) 888-8888',
                    'address' => '888 Customer St',
                    'address2' => 'Apt 8',
                    'city' => 'City',
                    'state' => 'ST',
                    'zipcode' => '88888',
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanGetACustomer()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();

        $response = $this->get('/api/customers/1');
        $response->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'address2' => $customer->address2,
                'city' => $customer->city,
                'state' => $customer->state,
                'zipcode' => $customer->zipcode,
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateACustomer()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Customer Name',
            'email' => 'customer@customer.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Customer St',
            'address2' => 'Apt 9',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '99999',
        ];
        $request = $this->post('/api/customers', $data);

        $request->assertCreated()
            ->assertJson([
                'name' => 'Customer Name',
                'email' => 'customer@customer.com',
                'phone' => '(999) 999-9999',
                'address' => '999 Customer St',
                'address2' => 'Apt 9',
                'city' => 'City',
                'state' => 'ST',
                'zipcode' => '99999',
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateACustomerWithoutTheRequiredFields()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => '',
            'email' => '',
            'phone' => '',
            'address' => '',
            'address2' => '',
            'city' => '',
            'state' => '',
            'zipcode' => '',
        ];
        $request = $this->post('/api/customers', $data);

        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                    'email' => [
                        'The email field is required.'
                    ],
                    'phone' => [
                        'The phone field is required.'
                    ],
                    'address' => [
                        'The address field is required.'
                    ],
                    'city' => [
                        'The city field is required.'
                    ],
                    'state' => [
                        'The state field is required.'
                    ],
                    'zipcode' => [
                        'The zipcode field is required.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateACustomerWithoutValidEmail()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Customer Name',
            'email' => 'customer',
            'phone' => '(999) 999-9999',
            'address' => '999 Customer St',
            'address2' => 'Apt 9',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '99999',
        ];
        $request = $this->post('/api/customers', $data);

        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email must be a valid email address.'
                    ],
                ]
            ]);
        
        $data['email'] = 'customer@';
        $request = $this->post('/api/customers', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email must be a valid email address.'
                    ],
                ]
            ]);
    
        $data['email'] = 'customer@customer';
        $request = $this->post('/api/customers', $data);
        $request->assertCreated();
    
        $data['email'] = 'customer@customer.com';
        $request = $this->post('/api/customers', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCantCreateACustomerWithWrongState()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Customer Name',
            'email' => 'customer@customer.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Customer St',
            'address2' => 'Apt 9',
            'city' => 'City',
            'state' => 'Florida',
            'zipcode' => '99999',
        ];
        $request = $this->post('/api/customers', $data);

        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'state' => [
                        'The state must be 2 characters.'
                    ],
                ]
            ]);
        
        $data['state'] = 'F';
        $request = $this->post('/api/customers', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'state' => [
                        'The state must be 2 characters.'
                    ],
                ]
            ]);
    
        $data['state'] = 'FL';
        $request = $this->post('/api/customers', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCantCreateACustomerWithWrongZipcode()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Customer Name',
            'email' => 'customer@customer.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Customer St',
            'address2' => 'Apt 9',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '9',
        ];
        $request = $this->post('/api/customers', $data);

        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'zipcode' => [
                        'The zipcode must be 5 characters.'
                    ],
                ]
            ]);
        
        $data['zipcode'] = '999999';
        $request = $this->post('/api/customers', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'zipcode' => [
                        'The zipcode must be 5 characters.'
                    ],
                ]
            ]);
    
        $data['zipcode'] = '99999';
        $request = $this->post('/api/customers', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCantCreateACustomerWithoutAddress2()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Customer Name',
            'email' => 'customer@customer.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Customer St',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '99999',
        ];

        $request = $this->post('/api/customers', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCanUpdateACustomer()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();

        $response = $this->get('/api/customers');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'address2' => $customer->address2,
                    'city' => $customer->city,
                    'state' => $customer->state,
                    'zipcode' => $customer->zipcode,
                ]
            ]);

        $data = [
            'name' => 'Customer Name',
            'email' => 'customer@customer.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Customer St',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '99999',
        ];

        $request = $this->put('/api/customers/1', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => 'Customer Name',
                'email' => 'customer@customer.com',
                'phone' => '(999) 999-9999',
                'address' => '999 Customer St',
                'city' => 'City',
                'state' => 'ST',
                'zipcode' => '99999',
            ]);

        $response = $this->get('/api/customers');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => 'Customer Name',
                    'email' => 'customer@customer.com',
                    'phone' => '(999) 999-9999',
                    'address' => '999 Customer St',
                    'city' => 'City',
                    'state' => 'ST',
                    'zipcode' => '99999',
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanDeleteACustomer()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();

        $response = $this->get('/api/customers');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'address2' => $customer->address2,
                    'city' => $customer->city,
                    'state' => $customer->state,
                    'zipcode' => $customer->zipcode,
                ]
            ]);

        $request = $this->delete('/api/customers/1');
        $request->assertOk()
            ->assertJson([]);

        $response = $this->get('/api/customers');
        $response->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }
}
