<?php

namespace Tests\Feature;

use App\Site;
use App\UserProgramTask;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProgramTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(UserProgramTask::class)->create([
            'site_id' =>  $this->site->id
        ]);


        $this->payload = [
            'user_id' => 1,
            'program_id' => 1,
            'program_task_id' => 1,
            'marks_obtained' => 0,
            'is_completed' => 0,
            'completion_date' => 'completion_date',
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/user_program_tasks', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "program_id"        =>  ["The program id field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_user_program_task()
    {
        $this->json('post', '/api/user_program_tasks', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'user_id' => 1,
                    'program_id' => 1,
                    'program_task_id' => 1,
                    'marks_obtained' => 0,
                    'is_completed' => 0,
                    'completion_date' => 'completion_date',
                ]
            ])
            ->assertJsonStructureExact([
                'data'   => [
                    'user_id',
                    'program_id',
                    'program_task_id',
                    'marks_obtained',
                    'is_completed',
                    'completion_date',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    /** @test */
    function list_of_user_program_tasks()
    {
        $this->disableEH();
        $this->json('GET', '/api/user_program_tasks', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'program_id'
                    ]
                ]
            ]);
        $this->assertCount(1, UserProgramTask::all());
    }

    /** @test */
    // function show_single_user_program_task()
    // {

    //     $this->json('get', "/api/user_program_tasks/1", [], $this->headers)
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'data'  => [
    //                 'user_id' => 1,
    //                 'program_id' => 1,
    //                 'program_task_id' => 1,
    //                 'marks_obtained' => 0,
    //                 'is_completed' => 0,
    //                 'completion_date' => 'completion_date',
    //             ]
    //         ]);
    // }

    // /** @test */
    // function update_single_user_program_task()
    // {
    //     $payload = [
    //         'user_id' => 1,
    //         'program_id' => 1,
    //         'program_task_id' => 1,
    //         'marks_obtained' => 0,
    //         'is_completed' => 0,
    //         'completion_date' => 'completion_date updated',
    //     ];

    //     $this->json('patch', '/api/user_program_tasks/1', $payload, $this->headers)
    //         ->assertStatus(200)
    //         ->assertJson([
    //             'data'    => [
    //                 'user_id' => 1,
    //                 'program_id' => 1,
    //                 'program_task_id' => 1,
    //                 'marks_obtained' => 0,
    //                 'is_completed' => 0,
    //                 'completion_date' => 'completion_date updated',
    //             ]
    //         ])
    //         ->assertJsonStructureExact([
    //             'data'  => [
    //                 'id',
    //                 'site_id',
    //                 'user_id',
    //                 'program_id',
    //                 'program_task_id',
    //                 'marks_obtained',
    //                 'is_completed',
    //                 'completion_date',
    //                 'created_at',
    //                 'updated_at',
    //             ]
    //         ]);
    // }

    /** @test */
    function show_single_user_program_task()
    {

        $this->json('get', "/api/user_program_tasks/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'user_id' => 1,
                    'program_id' => 1,
                    'program_task_id' => 1,
                    'marks_obtained' => 0,
                    'is_completed' => 0,
                    'completion_date' => 'completion_date',
                ]
            ]);
    }

    /** @test */
    function update_single_user_program_task()
    {
        $payload = [
            'user_id' => 1,
            'program_id' => 1,
            'program_task_id' => 1,
            'marks_obtained' => 0,
            'is_completed' => 0,
            'completion_date' => 'completion_date',
        ];

        $this->json('patch', '/api/user_program_tasks/1', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'    => [
                    'user_id' => 1,
                    'program_id' => 1,
                    'program_task_id' => 1,
                    'marks_obtained' => 0,
                    'is_completed' => 0,
                    'completion_date' => 'completion_date',
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'id',
                    'site_id',
                    'user_id',
                    'program_id',
                    'program_task_id',
                    'marks_obtained',
                    'is_completed',
                    'completion_date',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    function delete_user_program_task()
    {
        $this->json('delete', '/api/user_program_tasks/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, UserProgramTask::all());
    }
}
