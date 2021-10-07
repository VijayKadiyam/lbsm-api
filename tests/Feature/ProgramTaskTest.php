<?php

namespace Tests\Feature;

use App\ProgramTask;
use App\Site;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProgramTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(ProgramTask::class)->create([
            'site_id' =>  $this->site->id
        ]);


        $this->payload = [
            'program_id' => 1,
            'program_post_id' => 1,
            'serial_no' => 1,
            'task' => "task",
            'objective' => "objective",
            'material' => "material",
            'process' => "process",
            'no_of_contracts' => 0,
            'time_required' => 0,
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/program_tasks', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "program_id"        =>  ["The program id field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_program_task()
    {
        $this->json('post', '/api/program_tasks', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'program_id' => 1,
                    'program_post_id' => 1,
                    'serial_no' => 1,
                    'task' => "task",
                    'objective' => "objective",
                    'material' => "material",
                    'process' => "process",
                    'no_of_contracts' => 0,
                    'time_required' => 0,
                ]
            ])
            ->assertJsonStructureExact([
                'data'   => [
                    'program_id',
                    'program_post_id',
                    'serial_no',
                    'task',
                    'objective',
                    'material',
                    'process',
                    'no_of_contracts',
                    'time_required',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    /** @test */
    function list_of_program_tasks()
    {
        $this->disableEH();
        $this->json('GET', '/api/program_tasks', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'program_id'
                    ]
                ]
            ]);
        $this->assertCount(1, ProgramTask::all());
    }

    /** @test */
    function show_single_program_task()
    {

        $this->json('get', "/api/program_tasks/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'program_id' => 1,
                    'program_post_id' => 1,
                    'serial_no' => 1,
                    'task' => "task",
                    'objective' => "objective",
                    'material' => "material",
                    'process' => "process",
                    'no_of_contracts' => 0,
                    'time_required' => 0,
                ]
            ]);
    }

    /** @test */
    function update_single_program_task()
    {
        $payload = [
            'program_id' => 1,
            'program_post_id' => 1,
            'serial_no' => 1,
            'task' => "task",
            'objective' => "objective",
            'material' => "material",
            'process' => "process",
            'no_of_contracts' => 0,
            'time_required' => 0,
        ];

        $this->json('patch', '/api/program_tasks/1', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'    => [
                    'program_id' => 1,
                    'program_post_id' => 1,
                    'serial_no' => 1,
                    'task' => "task",
                    'objective' => "objective",
                    'material' => "material",
                    'process' => "process",
                    'no_of_contracts' => 0,
                    'time_required' => 0,
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'id',
                    'site_id',
                    'program_id',
                    'program_post_id',
                    'serial_no',
                    'task',
                    'objective',
                    'material',
                    'process',
                    'no_of_contracts',
                    'time_required',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    function delete_program_task()
    {
        $this->json('delete', '/api/program_tasks/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, ProgramTask::all());
    }
}
