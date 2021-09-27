<?php

namespace Tests\Feature;

use App\Site;
use App\UserProgramPost;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProgramPostTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(UserProgramPost::class)->create([
            'site_id' =>  $this->site->id
        ]);

        $this->payload = [
            'user_id' => 1,
            'program_id' => 1,
            'program_post_id' => 1,
            'promote_date' => 'Promote Date',
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/user_program_posts', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "user_id"        =>  ["The user id field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_UserProgramPost()
    {
        $this->json('post', '/api/user_program_posts', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'user_id' => 1,
                    'program_id' => 1,
                    'program_post_id' => 1,
                    'promote_date' => 'Promote Date',
                ]
            ])
            ->assertJsonStructureExact([
                'data'   => [
                    'user_id',
                    'program_id',
                    'program_post_id',
                    'promote_date',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    /** @test */
    function list_of_userProgramPosts()
    {
        $this->disableEH();
        $this->json('GET', '/api/user_program_posts', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'user_id',
                        'program_id',
                        'program_post_id',
                        'promote_date',
                    ]
                ]
            ]);
        $this->assertCount(1, UserProgramPost::all());
    }

    /** @test */
    function show_single_UserProgram()
    {

        $this->json('get', "/api/user_program_posts/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'user_id' => 1,
                    'program_id' => 1,
                    'program_post_id' => 1,
                    'promote_date' => 'Promote Date',
                ]
            ]);
    }

    /** @test */
    function update_single_UserProgram()
    {
        $payload = [
            'user_id' => 2,
            'program_id' => 2,
            'program_post_id' => 2,
            'promote_date' => 'Promote Date Updated',
        ];

        $this->json('patch', '/api/user_program_posts/1', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'    => [
                    'user_id' => 2,
                    'program_id' => 2,
                    'program_post_id' => 2,
                    'promote_date' => 'Promote Date Updated',
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'id',
                    'site_id',
                    'user_id',
                    'program_id',
                    'program_post_id',
                    'promote_date',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    function delete_UserProgram()
    {
        $this->disableEH();
        $this->json('delete', '/api/user_program_posts/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, UserProgramPost::all());
    }
}
