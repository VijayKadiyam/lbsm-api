<?php

namespace Tests\Feature;

use App\KarcoTask;
use App\Site;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KarcoTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(KarcoTask::class)->create([
            'site_id' =>  $this->site->id
        ]);

        $this->payload = [
            'department' => 'department',
            'status' =>        'status',
            'signed_on' =>        'signed_on',
            'video_title' =>        'video_title',
            'no_of_preview_watched' =>        'no_of_preview_watched',
            'no_of_video_watched' =>        'no_of_video_watched',
            'obtained_marks' =>        'obtained_marks',
            'total_marks' =>        'total_marks',
            'percentage' =>        'percentage',
            'done_on' =>        'done_on',
            'due_days' =>        'due_days',
            'assessment_status' =>        'assessment_status',
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/karco_tasks', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "department"        =>  ["The department field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_KarcoTask()
    {
        $this->json('post', '/api/karco_tasks', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'department' => 'department',
                    'status' =>        'status',
                    'signed_on' =>        'signed_on',
                    'video_title' =>        'video_title',
                    'no_of_preview_watched' =>        'no_of_preview_watched',
                    'no_of_video_watched' =>        'no_of_video_watched',
                    'obtained_marks' =>        'obtained_marks',
                    'total_marks' =>        'total_marks',
                    'percentage' =>        'percentage',
                    'done_on' =>        'done_on',
                    'due_days' =>        'due_days',
                    'assessment_status' =>        'assessment_status',
                ]
            ])
            ->assertJsonStructureExact([
                'data'   => [
                    'department',
                    'status',
                    'signed_on',
                    'video_title',
                    'no_of_preview_watched',
                    'no_of_video_watched',
                    'obtained_marks',
                    'total_marks',
                    'percentage',
                    'done_on',
                    'due_days',
                    'assessment_status',
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
        $this->json('GET', '/api/karco_tasks', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'department' => 'department',
                        'status' =>        'status',
                        'signed_on' =>        'signed_on',
                        'video_title' =>        'video_title',
                        'no_of_preview_watched' =>        'no_of_preview_watched',
                        'no_of_video_watched' =>        'no_of_video_watched',
                        'obtained_marks' =>        'obtained_marks',
                        'total_marks' =>        'total_marks',
                        'percentage' =>        'percentage',
                        'done_on' =>        'done_on',
                        'due_days' =>        'due_days',
                        'assessment_status' =>        'assessment_status',
                    ]
                ]
            ]);
        $this->assertCount(1, KarcoTask::all());
    }

    /** @test */
    function show_single_KarcoTask()
    {

        $this->json('get', "/api/karco_tasks/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'department' => 'department',
                    'status' =>        'status',
                    'signed_on' =>        'signed_on',
                    'video_title' =>        'video_title',
                    'no_of_preview_watched' =>        'no_of_preview_watched',
                    'no_of_video_watched' =>        'no_of_video_watched',
                    'obtained_marks' =>        'obtained_marks',
                    'total_marks' =>        'total_marks',
                    'percentage' =>        'percentage',
                    'done_on' =>        'done_on',
                    'due_days' =>        'due_days',
                    'assessment_status' =>        'assessment_status',
                ]
            ]);
    }

    /** @test */
    function update_single_KarcoTask()
    {
        $payload = [
            'department' => 'department',
            'status' =>        'status',
            'signed_on' =>        'signed_on',
            'video_title' =>        'video_title',
            'no_of_preview_watched' =>        'no_of_preview_watched',
            'no_of_video_watched' =>        'no_of_video_watched',
            'obtained_marks' =>        'obtained_marks',
            'total_marks' =>        'total_marks',
            'percentage' =>        'percentage',
            'done_on' =>        'done_on',
            'due_days' =>        'due_days',
            'assessment_status' =>        'assessment_status',
        ];

        $this->json('patch', '/api/karco_tasks/1', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'    => [
                    'department' => 'department',
                    'status' =>        'status',
                    'signed_on' =>        'signed_on',
                    'video_title' =>        'video_title',
                    'no_of_preview_watched' =>        'no_of_preview_watched',
                    'no_of_video_watched' =>        'no_of_video_watched',
                    'obtained_marks' =>        'obtained_marks',
                    'total_marks' =>        'total_marks',
                    'percentage' =>        'percentage',
                    'done_on' =>        'done_on',
                    'due_days' =>        'due_days',
                    'assessment_status' =>        'assessment_status',
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'id',
                    'site_id',
                    'ship_id',
                    'user_id',
                    'department',
                    'status',
                    'signed_on',
                    'video_title',
                    'no_of_preview_watched',
                    'no_of_video_watched',
                    'obtained_marks',
                    'total_marks',
                    'percentage',
                    'done_on',
                    'due_days',
                    'assessment_status',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    function delete_KarcoTask()
    {
        $this->disableEH();
        $this->json('delete', '/api/karco_tasks/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, KarcoTask::all());
    }
}
