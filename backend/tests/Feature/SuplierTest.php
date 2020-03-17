<?php

namespace Tests\Feature;

use App\Model\Suplier;
use App\Model\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuplierTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aUserCanGetAllSuplierSortedByNameAsc()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Suplier::class)->create([
            'name' => 'Suplier Name 2',
            'email' => 'suplier2@suplier.com',
            'phone' => '(888) 888-8888',
            'address' => '888 Suplier St',
            'address2' => 'Apt 8',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '88888',
        ]);
        factory(Suplier::class)->create([
            'name' => 'Suplier Name 1',
            'email' => 'suplier1@suplier.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Suplier St',
            'address2' => 'Apt 9',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '99999',
        ]);

        $response = $this->get('/api/supliers');
        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 2,
                    'name' => 'Suplier Name 1',
                    'email' => 'suplier1@suplier.com',
                    'phone' => '(999) 999-9999',
                    'address' => '999 Suplier St',
                    'address2' => 'Apt 9',
                    'city' => 'City',
                    'state' => 'ST',
                    'zipcode' => '99999',
                ],
                [
                    'id' => 1,
                    'name' => 'Suplier Name 2',
                    'email' => 'suplier2@suplier.com',
                    'phone' => '(888) 888-8888',
                    'address' => '888 Suplier St',
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
    public function aUserCanGetASuplier()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $suplier = factory(Suplier::class)->create();

        $response = $this->get('/api/supliers/1');
        $response->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => $suplier->name,
                'email' => $suplier->email,
                'phone' => $suplier->phone,
                'address' => $suplier->address,
                'address2' => $suplier->address2,
                'city' => $suplier->city,
                'state' => $suplier->state,
                'zipcode' => $suplier->zipcode,
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateASuplier()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Suplier Name',
            'email' => 'suplier@suplier.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Suplier St',
            'address2' => 'Apt 9',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '99999',
        ];
        $request = $this->post('/api/supliers', $data);

        $request->assertCreated()
            ->assertJson([
                'name' => 'Suplier Name',
                'email' => 'suplier@suplier.com',
                'phone' => '(999) 999-9999',
                'address' => '999 Suplier St',
                'address2' => 'Apt 9',
                'city' => 'City',
                'state' => 'ST',
                'zipcode' => '99999',
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateASuplierWithoutTheRequiredFields()
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
        $request = $this->post('/api/supliers', $data);

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
    public function aUserCantCreateASuplierWithoutValidEmail()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Suplier Name',
            'email' => 'suplier',
            'phone' => '(999) 999-9999',
            'address' => '999 Suplier St',
            'address2' => 'Apt 9',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '99999',
        ];
        $request = $this->post('/api/supliers', $data);

        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email must be a valid email address.'
                    ],
                ]
            ]);
        
        $data['email'] = 'suplier@';
        $request = $this->post('/api/supliers', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email must be a valid email address.'
                    ],
                ]
            ]);
    
        $data['email'] = 'suplier@suplier';
        $request = $this->post('/api/supliers', $data);
        $request->assertCreated();
    
        $data['email'] = 'suplier@suplier.com';
        $request = $this->post('/api/supliers', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCantCreateASuplierWithWrongState()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Suplier Name',
            'email' => 'suplier@suplier.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Suplier St',
            'address2' => 'Apt 9',
            'city' => 'City',
            'state' => 'Florida',
            'zipcode' => '99999',
        ];
        $request = $this->post('/api/supliers', $data);

        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'state' => [
                        'The state must be 2 characters.'
                    ],
                ]
            ]);
        
        $data['state'] = 'F';
        $request = $this->post('/api/supliers', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'state' => [
                        'The state must be 2 characters.'
                    ],
                ]
            ]);
    
        $data['state'] = 'FL';
        $request = $this->post('/api/supliers', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCantCreateASuplierWithWrongZipcode()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Suplier Name',
            'email' => 'suplier@suplier.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Suplier St',
            'address2' => 'Apt 9',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '9',
        ];
        $request = $this->post('/api/supliers', $data);

        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'zipcode' => [
                        'The zipcode must be 5 characters.'
                    ],
                ]
            ]);
        
        $data['zipcode'] = '999999';
        $request = $this->post('/api/supliers', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'zipcode' => [
                        'The zipcode must be 5 characters.'
                    ],
                ]
            ]);
    
        $data['zipcode'] = '99999';
        $request = $this->post('/api/supliers', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCantCreateASuplierWithoutAddress2()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Suplier Name',
            'email' => 'suplier@suplier.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Suplier St',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '99999',
        ];

        $request = $this->post('/api/supliers', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCanUpdateASuplier()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $suplier = factory(Suplier::class)->create();

        $response = $this->get('/api/supliers');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $suplier->name,
                    'email' => $suplier->email,
                    'phone' => $suplier->phone,
                    'address' => $suplier->address,
                    'address2' => $suplier->address2,
                    'city' => $suplier->city,
                    'state' => $suplier->state,
                    'zipcode' => $suplier->zipcode,
                ]
            ]);

        $data = [
            'name' => 'Suplier Name',
            'email' => 'suplier@suplier.com',
            'phone' => '(999) 999-9999',
            'address' => '999 Suplier St',
            'city' => 'City',
            'state' => 'ST',
            'zipcode' => '99999',
        ];

        $request = $this->put('/api/supliers/1', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => 'Suplier Name',
                'email' => 'suplier@suplier.com',
                'phone' => '(999) 999-9999',
                'address' => '999 Suplier St',
                'city' => 'City',
                'state' => 'ST',
                'zipcode' => '99999',
            ]);

        $response = $this->get('/api/supliers');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => 'Suplier Name',
                    'email' => 'suplier@suplier.com',
                    'phone' => '(999) 999-9999',
                    'address' => '999 Suplier St',
                    'city' => 'City',
                    'state' => 'ST',
                    'zipcode' => '99999',
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanDeleteASuplier()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $suplier = factory(Suplier::class)->create();

        $response = $this->get('/api/supliers');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $suplier->name,
                    'email' => $suplier->email,
                    'phone' => $suplier->phone,
                    'address' => $suplier->address,
                    'address2' => $suplier->address2,
                    'city' => $suplier->city,
                    'state' => $suplier->state,
                    'zipcode' => $suplier->zipcode,
                ]
            ]);

        $request = $this->delete('/api/supliers/1');
        $request->assertOk()
            ->assertJson([]);

        $response = $this->get('/api/supliers');
        $response->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }
}
