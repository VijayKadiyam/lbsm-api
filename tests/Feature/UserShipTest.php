<?php

namespace Tests\Feature;

use App\Site;
use App\UserShip;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserShipTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(UserShip::class)->create([
            'site_id' =>  $this->site->id
        ]);

        $this->payload = [
            'user_id' =>        1,
            'ship_id' =>        1,
            'from_date' =>      'from_date',
            'to_date' =>        'to_date',
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/user_ships', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "user_id"        =>  ["The user id field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_user_ship()
    {
        $this->disableEH();

        $this->json('post', '/api/user_ships', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'user_id' =>        1,
                    'ship_id' =>        1,
                    'from_date' =>      'from_date',
                    'to_date' =>        'to_date',
                ]
            ])
            ->assertJsonStructureExact([
                'data'   => [
                    'user_id',
                    'ship_id',
                    'from_date',
                    'to_date',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    /** @test */
    function list_of_user_ships()
    {
        $this->disableEH();
        $this->json('GET', '/api/user_ships', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'user_id',
                        'ship_id',
                        'from_date',
                        'to_date',
                    ]
                ]
            ]);
        $this->assertCount(1, UserShip::all());
    }

    /** @test */
    function show_single_user_ship()
    {

        $this->json('get', "/api/user_ships/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'user_id' =>        1,
                    'ship_id' =>        1,
                    'from_date' =>      'from_date',
                    'to_date' =>        'to_date',
                ]
            ]);
    }

    /** @test */
    function update_single_user_ship()
    {
        $payload = [
            'user_id' =>        2,
            'ship_id' =>        2,
            'from_date' =>      'from_date Updated',
            'to_date' =>        'to_date Updated',
        ];

        $this->json('patch', '/api/user_ships/1', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'    => [
                    'user_id' =>        2,
                    'ship_id' =>        2,
                    'from_date' =>      'from_date Updated',
                    'to_date' =>        'to_date Updated',
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'id',
                    'site_id',
                    'user_id',
                    'ship_id',
                    'from_date',
                    'to_date',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    function delete_user_ship()
    {
        $this->json('delete', '/api/user_ships/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, UserShip::all());
    }
}
