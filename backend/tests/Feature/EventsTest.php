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
}
