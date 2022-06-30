<?php

namespace Tests\Feature;

use App\DumpProgramTask;
use App\Site;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DumpProgramTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(DumpProgramTask::class)->create([
            'site_id' =>  $this->site->id
        ]);


        $this->payload = [
            'user_id' => 1,
            'program_id' => 1,
            'program_task_id' => 1,
            'marks_obtained' => 0,
            'is_completed' => 0,
            'completion_date' => 'completion_date',
            'subject' => 'subject',
            'body' => 'body',
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/dump_program_tasks', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "subject"        =>  ["The subject field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_dump_program_task()
    {
        $this->json('post', '/api/dump_program_tasks', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'user_id' => 1,
                    'program_id' => 1,
                    'program_task_id' => 1,
                    'marks_obtained' => 0,
                    'is_completed' => 0,
                    'completion_date' => 'completion_date',
                    'subject' => 'subject',
                    'body' => 'body',
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
                    'subject',
                    'body',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    /** @test */
    function list_of_dump_program_tasks()
    {
        $this->disableEH();
        $this->json('GET', '/api/dump_program_tasks', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'subject'
                    ]
                ]
            ]);
        $this->assertCount(1, DumpProgramTask::all());
    }


    /** @test */
    function show_single_DumpProgramTask()
    {
        $this->disableEH();
        $this->json('get', "/api/dump_program_tasks/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'user_id' => 1,
                    'program_id' => 1,
                    'program_task_id' => 1,
                    'marks_obtained' => 0,
                    'is_completed' => 0,
                    'completion_date' => 'completion_date',
                    'subject' => 'subject',
                    'body' => 'body',
                ]
            ]);
    }

    /** @test */
    function update_single_TaskTask()
    {
        $payload = [
            'user_id' => 1,
            'program_id' => 1,
            'program_task_id' => 1,
            'marks_obtained' => 0,
            'is_completed' => 0,
            'completion_date' => 'completion_date',
            'subject' => 'subject',
            'body' => 'body',
        ];

        $this->json('patch', '/api/dump_program_tasks/1', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'    => [
                    'user_id' => 1,
                    'program_id' => 1,
                    'program_task_id' => 1,
                    'marks_obtained' => 0,
                    'is_completed' => 0,
                    'completion_date' => 'completion_date',
                    'subject' => 'subject',
                    'body' => 'body',
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'id',
                    'site_id',
                    'user_id',
                    'program_id',
                    'user_program_id',
                    'program_task_id',
                    'marks_obtained',
                    'is_completed',
                    'completion_date',
                    'imagepath1',
                    'imagepath2',
                    'imagepath3',
                    'imagepath4',
                    'ship_id',
                    'from_date',
                    'to_date',
                    'active',
                    'remark',
                    'subject',
                    'body',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    function delete_dump_program_task()
    {
        $this->json('delete', '/api/dump_program_tasks/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, DumpProgramTask::all());
    }
}
