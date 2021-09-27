<?php

namespace Tests\Feature;

use App\ProgramPost;
use App\Site;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProgramPostTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(ProgramPost::class)->create([
            'site_id' =>  $this->site->id
        ]);

        $this->payload = [
            'program_id' => 1,
            'serial_no' => 1,
            'post_id' => 1,
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/program_posts', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "program_id"        =>  ["The program id field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_ProgramPost()
    {
        $this->json('post', '/api/program_posts', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'program_id' => 1,
                    'serial_no' => 1,
                    'post_id' => 1,
                ]
            ])
            ->assertJsonStructureExact([
                'data'   => [
                    'program_id',
                    'serial_no',
                    'post_id',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    /** @test */
    function list_of_program_posts()
    {
        $this->disableEH();
        $this->json('GET', '/api/program_posts', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'program_id',
                        'serial_no',
                        'post_id',
                    ]
                ]
            ]);
        $this->assertCount(1, ProgramPost::all());
    }

    /** @test */
    function show_single_ProgramPost()
    {

        $this->json('get', "/api/program_posts/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'program_id' => 1,
                    'serial_no' => 1,
                    'post_id' => 1,
                ]
            ]);
    }

    /** @test */
    function update_single_Program()
    {
        $payload = [
            'program_id' => 2,
            'serial_no' => 2,
            'post_id' => 2,
        ];

        $this->json('patch', '/api/program_posts/1', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'    => [
                    'program_id' => 2,
                    'serial_no' => 2,
                    'post_id' => 2,
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'id',
                    'site_id',
                    'program_id',
                    'serial_no',
                    'post_id',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    function delete_Program()
    {
        $this->disableEH();
        $this->json('delete', '/api/program_posts/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, ProgramPost::all());
    }
}
