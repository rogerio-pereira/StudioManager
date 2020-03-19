<?php

// namespace Tests\Feature;

// use Carbon\Carbon;
// use App\Model\Team;
// use App\Model\User;
// use Tests\TestCase;
// use App\Model\Event;
// use App\Model\Customer;
// use App\Model\EventTeam;
// use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;

// class ScheduleTest extends TestCase
// {
//     use RefreshDatabase;

//     /**
//      * @test
//      */
//     public function aUserCanGetScheduleForTodayWithoutStartDateAndAndDate()
//     {
//         $this->withoutExceptionHandling();
//         $this->actingAs(factory(User::class)->create(), 'api');
//         $customer = factory(Customer::class)->create();
//         $team1 = factory(Team::class)->create();
//         $team2 = factory(Team::class)->create();
//         $today = Carbon::today();
//         $nextWeek = $today->addWeek();

//         factory(Event::class)->create([
//             'customer_id' => 1,
//             'date' => $nextWeek->toDateTimeString(),
//             'place' => 'Place 1 Address'
//         ]);
//         factory(EventTeam::class)->create([
//             'event_id' => 1,
//             'member_id' => 1,
//         ]);

//         factory(Event::class)->create([
//             'customer_id' => 1,
//             'date' => $today->toDateTimeString(),
//             'place' => 'Place 2 Address'
//         ]);
//         factory(EventTeam::class)->create([
//             'event_id' => 2,
//             'member_id' => 1,
//         ]);
//         factory(EventTeam::class)->create([
//             'event_id' => 2,
//             'member_id' => 2,
//         ]);

//         $response = $this->get('/api/schedule');

//         $response->assertOk()
//             ->assertJsonCount(1)
//             ->assertJson([
//                 [
//                     'id' => 2,
//                     'date' => $today->toDateTimeString(),
//                     'place' => 'Place 2 Address',
//                     'customer' => [
//                         'id' => 1,
//                         'name' => $customer->name
//                     ],
//                     'team' => [
//                         [
//                             'id' => 1,
//                             'name' => $team1->name
//                         ],
//                         [
//                             'id' => 2,
//                             'name' => $team2->name
//                         ]
//                     ]
//                 ],
//             ]);
//     }

//     /**
//      * @test
//      */
//     public function aUserCanGetScheduleForDateWithoutEndDate()
//     {
//         $this->actingAs(factory(User::class)->create(), 'api');
//         $customer = factory(Customer::class)->create();
//         $team1 = factory(Team::class)->create();
//         $team2 = factory(Team::class)->create();
//         $today = Carbon::now();
//         $nextWeek = $today->addWeek();

//         factory(Event::class)->create([
//             'customer_id' => 1,
//             'date' => $nextWeek->toDateTimeString(),
//             'place' => 'Place 1 Address'
//         ]);
//         factory(EventTeam::class)->create([
//             'event_id' => 1,
//             'member_id' => 1,
//         ]);

//         factory(Event::class)->create([
//             'customer_id' => 1,
//             'date' => $today->toDateTimeString(),
//             'place' => 'Place 2 Address'
//         ]);
//         factory(EventTeam::class)->create([
//             'event_id' => 2,
//             'member_id' => 1,
//         ]);
//         factory(EventTeam::class)->create([
//             'event_id' => 2,
//             'member_id' => 2,
//         ]);

//         $response = $this->get('/api/schedule/'.$nextWeek->toDateString());

//         $response->assertOk()
//             ->assertJsonCount(1)
//             ->assertJson([
//                 [
//                     'id' => 1,
//                     'date' => $nextWeek->toDateTimeString(),
//                     'place' => 'Place 1 Address',
//                     'customer' => [
//                         'id' => 1,
//                         'name' => $customer->name
//                     ],
//                     'team' => [
//                         [
//                             'id' => 1,
//                             'name' => $team1->name
//                         ]
//                     ]
//                 ],
//             ]);
//     }
    
//     /**
//      * @test
//      */
//     public function aUserCanGetScheduleWithStartDateAndEndDate()
//     {
//         $this->actingAs(factory(User::class)->create(), 'api');
//         $customer = factory(Customer::class)->create();
//         $team1 = factory(Team::class)->create();
//         $team2 = factory(Team::class)->create();
//         $today = Carbon::now();
//         $nextWeek = $today->addWeek();

//         factory(Event::class)->create([
//             'customer_id' => 1,
//             'date' => $nextWeek->toDateTimeString(),
//             'place' => 'Place 1 Address'
//         ]);
//         factory(EventTeam::class)->create([
//             'event_id' => 1,
//             'member_id' => 1,
//         ]);

//         factory(Event::class)->create([
//             'customer_id' => 1,
//             'date' => $today->toDateTimeString(),
//             'place' => 'Place 2 Address'
//         ]);
//         factory(EventTeam::class)->create([
//             'event_id' => 2,
//             'member_id' => 1,
//         ]);
//         factory(EventTeam::class)->create([
//             'event_id' => 2,
//             'member_id' => 2,
//         ]);

//         factory(Event::class)->create([
//             'customer_id' => 1,
//             'date' => $nextWeek->addWeek()->toDateTimeString(),
//             'place' => 'Place 2 Address'
//         ]);
//         factory(EventTeam::class)->create([
//             'event_id' => 2,
//             'member_id' => 1,
//         ]);
//         factory(EventTeam::class)->create([
//             'event_id' => 2,
//             'member_id' => 2,
//         ]);

//         $response = $this->get('/api/schedule/'.$today->toDateString().'/'.$nextWeek->toDateString());

//         $response->assertOk()
//             ->assertJsonCount(1)
//             ->assertJson([
//                 [
//                     'id' => 2,
//                     'date' => $today->toDateTimeString(),
//                     'place' => 'Place 2 Address',
//                     'customer' => [
//                         'id' => 1,
//                         'name' => $customer->name
//                     ],
//                     'team' => [
//                         [
//                             'id' => 1,
//                             'name' => $team1->name
//                         ],
//                         [
//                             'id' => 2,
//                             'name' => $team2->name
//                         ]
//                     ]
//                 ],
//                 [
//                     'id' => 1,
//                     'date' => $nextWeek->toDateTimeString(),
//                     'place' => 'Place 1 Address',
//                     'customer' => [
//                         'id' => 1,
//                         'name' => $customer->name
//                     ],
//                     'team' => [
//                         [
//                             'id' => 1,
//                             'name' => $team1->name
//                         ]
//                     ]
//                 ],
//             ]);
//     }
// }
