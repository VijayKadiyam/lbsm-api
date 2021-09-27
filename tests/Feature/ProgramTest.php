<?php

namespace Tests\Feature;

use App\Program;
use App\Site;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProgramTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(Program::class)->create([
            'site_id' =>  $this->site->id
        ]);

        $this->payload = [
            'program_name' => 'Program Name',
            'program_description' => 'Program Description',
            'instructor' => 'Instructor',
            'hours' => 1,
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/programs', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "program_name"        =>  ["The program name field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_Program()
    {
        $this->json('post', '/api/programs', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'program_name' => 'Program Name',
                    'program_description' => 'Program Description',
                    'instructor' => 'Instructor',
                    'hours' => 1,
                ]
            ])
            ->assertJsonStructureExact([
                'data'   => [
                    'program_name',
                    'program_description',
                    'instructor',
                    'hours',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    /** @test */
    function list_of_programs()
    {
        $this->disableEH();
        $this->json('GET', '/api/programs', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'program_name',
                        'program_description',
                        'instructor',
                        'hours',
                    ]
                ]
            ]);
        $this->assertCount(1, Program::all());
    }

    /** @test */
    function show_single_Program()
    {

        $this->json('get', "/api/programs/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'program_name' => 'Program Name',
                    'program_description' => 'Program Description',
                    'instructor' => 'Instructor',
                    'hours' => 1,
                ]
            ]);
    }

    /** @test */
    function update_single_Program()
    {
        $payload = [
            'program_name' => 'Program Name Updated',
            'program_description' => 'Program Description Updated',
            'instructor' => 'Instructor Updated',
            'hours' => 2,
        ];

        $this->json('patch', '/api/programs/1', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'    => [
                    'program_name' => 'Program Name Updated',
                    'program_description' => 'Program Description Updated',
                    'instructor' => 'Instructor Updated',
                    'hours' => 2,
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'id',
                    'site_id',
                    'program_name',
                    'program_description',
                    'instructor',
                    'hours',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    function delete_Program()
    {
        $this->disableEH();
        $this->json('delete', '/api/programs/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, Program::all());
    }
}
