<?php

namespace Tests\Feature;

use Carbon\Carbon;
use App\Model\Team;
use App\Model\User;
use Tests\TestCase;
use App\Model\Event;
use App\Model\Customer;
use App\Model\EventTeam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventsTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     */
    public function aUserCanGetAllEventsOrderedByDate()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $today = Carbon::now();
        $nextWeek = $today->addWeek();

        factory(Event::class)->create([
            'customer_id' => 1,
            'date' => $nextWeek->toDateTimeString(),
        ]);
        factory(EventTeam::class)->create([
            'event_id' => 1,
            'member_id' => 1,
        ]);

        factory(Event::class)->create([
            'customer_id' => 1,
            'date' => $today->toDateTimeString(),
        ]);
        factory(EventTeam::class)->create([
            'event_id' => 2,
            'member_id' => 1,
        ]);
        factory(EventTeam::class)->create([
            'event_id' => 2,
            'member_id' => 2,
        ]);

        $response = $this->get('/api/events');
        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 1,
                    'date' => $today->toDateTimeString(),
                    'customer' => [
                        'id' => 1,
                        'name' => $customer->name
                    ],
                    'team' => [
                        [
                            'id' => 1,
                            'name' => $team1->name
                        ]
                    ]
                ],
                [
                    'id' => 2,
                    'date' => $nextWeek->toDateTimeString(),
                    'customer' => [
                        'id' => 1,
                        'name' => $customer->name
                    ],
                    'team' => [
                        [
                            'id' => 1,
                            'name' => $team1->name
                        ],
                        [
                            'id' => 2,
                            'name' => $team2->name
                        ]
                    ]
                ]
            ]);
    }
    
    /**
     * @test
     */
    public function aUserCanGetAEvent()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $today = Carbon::now();

        factory(Event::class)->create([
            'customer_id' => 1,
            'date' => $today->toDateTimeString(),
        ]);
        factory(EventTeam::class)->create([
            'event_id' => 1,
            'member_id' => 1,
        ]);
        factory(EventTeam::class)->create([
            'event_id' => 1,
            'member_id' => 2,
        ]);

        $response = $this->get('/api/events/1');
        $response->assertOk()
            ->assertJson([
                'id' => 1,
                'date' => $today->toDateTimeString(),
                'customer' => [
                    'id' => 1,
                    'name' => $customer->name
                ],
                'team' => [
                    [
                        'id' => 1,
                        'name' => $team1->name
                    ],
                    [
                        'id' => 2,
                        'name' => $team2->name
                    ]
                ]
            ]);
    }
    
    /**
     * @test
     */
    public function aUserCanCreateAnEvent()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $date = Carbon::now()->nextWeekendDay();

        $data = [
            'customer_id' => 1,
            'date' => $date->toDateTimeString(),
            'team' => [1, 2]
        ];

        $response = $this->post('/api/events', $data);
        $response->assertCreated()
            ->assertJson([
                'id' => 1,
                'date' => $date->toDateTimeString(),
                'customer' => [
                    'id' => 1,
                    'name' => $customer->name
                ],
                'team' => [
                    [
                        'id' => 1,
                        'name' => $team1->name
                    ],
                    [
                        'id' => 2,
                        'name' => $team2->name
                    ]
                ]
            ]);
    }
    
    /**
     * @test
     */
    public function aUserCantCreateAnEventWithoutRequiredFields()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $date = Carbon::now()->nextWeekendDay();

        $data = [
            // 'customer_id' => 1,
            // 'date' => $date->toDateTimeString(),
            // 'team' => [1, 2]
        ];

        $response = $this->post('/api/events', $data);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'customer_id' => [
                        'The customer id field is required.'
                    ],
                    'date' => [
                        'The date field is required.'
                    ],
                    'team' => [
                        'The team field is required.'
                    ],
                ]
            ]);
    }
    
    /**
     * @test
     */
    public function aUserCantCreateAnEventWithInvalidCustomerIdField()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $date = Carbon::now()->nextWeekendDay();

        $data = [
            'customer_id' => 'customer',
            'date' => $date->toDateTimeString(),
            'team' => [1, 2]
        ];

        $response = $this->post('/api/events', $data);
        $response->assertStatus(422)
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
    public function aUserCantCreateAnEventWithWrongCustomerIdField()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        // $customer = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $date = Carbon::now()->nextWeekendDay();

        $data = [
            'customer_id' => 1,
            'date' => $date->toDateTimeString(),
            'team' => [1, 2]
        ];

        $response = $this->post('/api/events', $data);
        $response->assertStatus(422)
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
    public function aUserCantCreateAnEventWithInvalidDateField()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        //$date = Carbon::now()->nextWeekendDay();

        $data = [
            'customer_id' => 1,
            'date' => 'date',
            'team' => [1, 2]
        ];

        $response = $this->post('/api/events', $data);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'date' => [
                        'The date is not a valid date.'
                    ],
                ]
            ]);
    }
    
    /**
     * @test
     */
    public function aUserCantCreateAnEventWithDateFieldInThePast()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $date = Carbon::now()->subDay();

        $data = [
            'customer_id' => 1,
            'date' => $date->toDateTimeString(),
            'team' => [1, 2]
        ];

        $response = $this->post('/api/events', $data);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'date' => [
                        'The date must be a date after or equal to today.'
                    ],
                ]
            ]);

        $data['date'] = Carbon::now()->toDateTimeString();
        $response = $this->post('/api/events', $data);
        $response->assertCreated();

        $data['date'] = Carbon::now()->addDay()->toDateTimeString();
        $response = $this->post('/api/events', $data);
        $response->assertCreated();
    }
    
    /**
     * @test
     */
    public function aUserCantCreateAnEventWithInvalidTeam()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $date = Carbon::now()->nextWeekendDay();

        $data = [
            'customer_id' => 1,
            'date' => $date,
            'team' => 1
        ];

        $response = $this->post('/api/events', $data);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'team' => [
                        'The team must be an array.'
                    ],
                ]
            ]);
    }
    
    /**
     * @test
     */
    public function aUserCantCreateAnEventWithWrongTeam()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $date = Carbon::now()->nextWeekendDay();

        $data = [
            'customer_id' => 1,
            'date' => $date,
            'team' => [1, 2, 3]
        ];

        $response = $this->post('/api/events', $data);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'team' => [
                        'The selected team is invalid.'
                    ],
                ]
            ]);

        $data['team'] = [1,2];
        $response = $this->post('/api/events', $data);
        $response->assertCreated();
    }
    
    /**
     * @test
     */
    public function aUserCanUpdateAnEvent()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $customer2 = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $team3 = factory(Team::class)->create();
        $team4 = factory(Team::class)->create();
        $event = factory(Event::class)->create([
            'date' => Carbon::now()->addWeek()->toDateTimeString(),
            'customer_id' => 1,
        ]);
        factory(EventTeam::class)->create([
            'event_id' => 1,
            'member_id' => 1,
        ]);
        factory(EventTeam::class)->create([
            'event_id' => 1,
            'member_id' => 2,
        ]);

        $response = $this->get('/api/events');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'date' => Carbon::now()->addWeek()->toDateTimeString(),
                    'customer' => [
                        'id' => 1,
                        'name' => $customer1->name
                    ],
                    'team' => [
                        [
                            'id' => 1,
                            'name' => $team1->name
                        ],
                        [
                            'id' => 2,
                            'name' => $team2->name
                        ]
                    ]
                ]
            ]);

        $data = [
            'customer_id' => 2,
            'date' => Carbon::now()->addMonth()->toDateTimeString(),
            'team' => [3, 4]
        ];

        $request = $this->put('/api/events/1', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'date' => Carbon::now()->addMonth()->toDateTimeString(),
                'customer' => [
                    'id' => 2,
                    'name' => $customer2->name
                ],
                'team' => [
                    [
                        'id' => 3,
                        'name' => $team3->name
                    ],
                    [
                        'id' => 4,
                        'name' => $team4->name
                    ]
                ]
            ]);


        $response = $this->get('/api/events');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'date' => Carbon::now()->addMonth()->toDateTimeString(),
                    'customer' => [
                        'id' => 2,
                        'name' => $customer2->name
                    ],
                    'team' => [
                        [
                            'id' => 3,
                            'name' => $team3->name
                        ],
                        [
                            'id' => 4,
                            'name' => $team4->name
                        ]
                    ]
                ]
            ]);
    }
    
    /**
     * @test
     */
    public function aUserCanDeleteAnEvent()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $customer1 = factory(Customer::class)->create();
        $team1 = factory(Team::class)->create();
        $team2 = factory(Team::class)->create();
        $event = factory(Event::class)->create([
            'customer_id' => 1,
        ]);
        factory(EventTeam::class)->create([
            'event_id' => 1,
            'member_id' => 1,
        ]);
        factory(EventTeam::class)->create([
            'event_id' => 1,
            'member_id' => 2,
        ]);

        $response = $this->get('/api/events');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'date' => $event->date->toDateTimeString(),
                    'customer' => [
                        'id' => 1,
                        'name' => $customer1->name
                    ],
                    'team' => [
                        [
                            'id' => 1,
                            'name' => $team1->name
                        ],
                        [
                            'id' => 2,
                            'name' => $team2->name
                        ]
                    ]
                ]
            ]);

        $request = $this->delete('/api/events/1');
        $request->assertOk()
            ->assertJson([]);

        $response = $this->get('/api/events');
        $response->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);

        $this->assertDatabaseMissing('event_team', [
            'id' => 1,
            'event_id' => 1,
            'member_id' => 1,
        ]);

        $this->assertDatabaseMissing('event_team', [
            'id' => 2,
            'event_id' => 1,
            'member_id' => 2,
        ]);
    }
}
