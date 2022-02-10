<?php

namespace Tests\Feature;

use App\Site;
use App\VideotelTask;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideotelTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(VideotelTask::class)->create([
            'site_id' =>  $this->site->id
        ]);

        $this->payload = [
            'training_title' => 'training_title',
            'module' => 'module',
            'type' => 'type',
            'date' => 'date',
            'duration' => 'duration',
            'score' => 'score',
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/videotel_tasks', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "training_title"        =>  ["The training title field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_VideotelTask()
    {
        $this->json('post', '/api/videotel_tasks', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'training_title' => 'training_title',
                    'module' => 'module',
                    'type' => 'type',
                    'date' => 'date',
                    'duration' => 'duration',
                    'score' => 'score',
                ]
            ])
            ->assertJsonStructureExact([
                'data'   => [
                    'training_title',
                    'module',
                    'type',
                    'date',
                    'duration',
                    'score',
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
        $this->json('GET', '/api/videotel_tasks', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'training_title' => 'training_title',
                        'module' => 'module',
                        'type' => 'type',
                        'date' => 'date',
                        'duration' => 'duration',
                        'score' => 'score',
                    ]
                ]
            ]);
        $this->assertCount(1, VideotelTask::all());
    }

    /** @test */
    function show_single_VideotelTask()
    {

        $this->json('get', "/api/videotel_tasks/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'training_title' => 'training_title',
                    'module' => 'module',
                    'type' => 'type',
                    'date' => 'date',
                    'duration' => 'duration',
                    'score' => 'score',
                ]
            ]);
    }

    /** @test */
    function update_single_VideotelTask()
    {
        $payload = [
            'training_title' => 'training_title',
            'module' => 'module',
            'type' => 'type',
            'date' => 'date',
            'duration' => 'duration',
            'score' => 'score',
        ];

        $this->json('patch', '/api/videotel_tasks/1', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'    => [
                    'training_title' => 'training_title',
                    'module' => 'module',
                    'type' => 'type',
                    'date' => 'date',
                    'duration' => 'duration',
                    'score' => 'score',
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'id',
                    'site_id',
                    'ship_id',
                    'user_id',
                    'training_title',
                    'module',
                    'type',
                    'date',
                    'duration',
                    'score',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    function delete_VideotelTask()
    {
        $this->disableEH();
        $this->json('delete', '/api/videotel_tasks/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, VideotelTask::all());
    }
}
