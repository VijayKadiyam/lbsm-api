<?php

namespace Tests\Feature;

use App\Site;
use App\UserITest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserITestTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(UserITest::class)->create([
            'site_id' =>  $this->site->id
        ]);

        $this->payload = [
            'user_id' => 1,
            'date' => 'date',
            'percentage' => 10.00,
            'status' => 'status',
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/user_i_tests', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "user_id"        =>  ["The user id field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_user_i_test()
    {
        $this->disableEH();

        $this->json('post', '/api/user_i_tests', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'user_id' => 1,
                    'date' => 'date',
                    'percentage' => 10.00,
                    'status' => 'status',
                ]
            ])
            ->assertJsonStructureExact([
                'data'   => [
                    'user_id',
                    'date',
                    'percentage',
                    'status',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    /** @test */
    function list_of_user_i_tests()
    {
        $this->disableEH();
        $this->json('GET', '/api/user_i_tests', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'user_id',
                        'date',
                        'percentage',
                        'status',
                    ]
                ]
            ]);
        $this->assertCount(1, UserITest::all());
    }

    /** @test */
    function show_single_user_i_test()
    {

        $this->json('get', "/api/user_i_tests/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'user_id' => 1,
                    'date' => 'date',
                    'percentage' => 10.00,
                    'status' => 'status',
                ]
            ]);
    }

    /** @test */
    function update_single_user_i_test()
    {
        $payload = [
            'user_id' => 2,
            'date' => 'date Updated',
            'percentage' => 12.00,
            'status' => 'status Updated',
        ];

        $this->json('patch', '/api/user_i_tests/1', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'    => [
                    'user_id' => 2,
                    'date' => 'date Updated',
                    'percentage' => 12.00,
                    'status' => 'status Updated',
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'id',
                    'site_id',
                    'user_id',
                    'date',
                    'percentage',
                    'status',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    function delete_user_i_test()
    {
        $this->json('delete', '/api/user_i_tests/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, UserITest::all());
    }
}
