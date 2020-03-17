<?php

namespace Tests\Feature;

use App\Model\Team;
use App\Model\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aUserCanGetAllTeamSortedByNameAsc()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Team::class)->create([
            'name' => 'Name 2',
            'email' => 'team2@team.com',
            'phone' => '(888) 888-8888',
            'own_equipment' => true,
        ]);
        factory(Team::class)->create([
            'name' => 'Name 1',
            'email' => 'team1@team.com',
            'phone' => '(999) 999-9999',
            'own_equipment' => false,
        ]);

        $response = $this->get('/api/team');
        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 2,
                    'name' => 'Name 1',
                    'email' => 'team1@team.com',
                    'phone' => '(999) 999-9999',
                    'own_equipment' => false,
                ],
                [
                    'id' => 1,
                    'name' => 'Name 2',
                    'email' => 'team2@team.com',
                    'phone' => '(888) 888-8888',
                    'own_equipment' => true,
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanGetATeamMember()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $team = factory(Team::class)->create();

        $response = $this->get('/api/team/1');
        $response->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => $team->name,
                'email' => $team->email,
                'phone' => $team->phone,
                'own_equipment' => $team->own_equipment,
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateATeamMember()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Name 1',
            'email' => 'team1@team.com',
            'phone' => '(999) 999-9999',
            'own_equipment' => false,
        ];
        $request = $this->post('/api/team', $data);

        $request->assertCreated()
            ->assertJson([
                'id' => 1,
                'name' => 'Name 1',
                'email' => 'team1@team.com',
                'phone' => '(999) 999-9999',
                'own_equipment' => false,
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATeamWithoutTheRequiredFields()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => '',
            'email' => '',
            'phone' => '',
        ];
        $request = $this->post('/api/team', $data);

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
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateATeamWithoutValidEmail()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Name',
            'email' => 'team',
            'phone' => '(999) 999-9999',
            'own_equipment' => false,
        ];
        $request = $this->post('/api/team', $data);

        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email must be a valid email address.'
                    ],
                ]
            ]);
        
        $data['email'] = 'team@';
        $request = $this->post('/api/team', $data);
        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email must be a valid email address.'
                    ],
                ]
            ]);
    
        $data['email'] = 'team@team';
        $request = $this->post('/api/team', $data);
        $request->assertCreated();
    
        $data['email'] = 'team@team.com';
        $request = $this->post('/api/team', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCanUpdateATeam()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $team = factory(Team::class)->create(['own_equipment' => true]);

        $response = $this->get('/api/team');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $team->name,
                    'email' => $team->email,
                    'phone' => $team->phone,
                    'own_equipment' => true,
                ]
            ]);

        $data = [
            'name' => 'Name',
            'email' => 'team@team.com',
            'phone' => '(999) 999-9999',
            'own_equipment' => false,
        ];

        $request = $this->put('/api/team/1', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => 'Name',
                'email' => 'team@team.com',
                'phone' => '(999) 999-9999',
                'own_equipment' => false,
            ]);

        $response = $this->get('/api/team');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => 'Name',
                    'email' => 'team@team.com',
                    'phone' => '(999) 999-9999',
                    'own_equipment' => false,
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanDeleteATeam()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $team = factory(Team::class)->create();

        $response = $this->get('/api/team');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $team->name,
                    'email' => $team->email,
                    'phone' => $team->phone,
                    'own_equipment' => $team->own_equipment,
                ]
            ]);

        $request = $this->delete('/api/team/1');
        $request->assertOk()
            ->assertJson([]);

        $response = $this->get('/api/team');
        $response->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }
}
