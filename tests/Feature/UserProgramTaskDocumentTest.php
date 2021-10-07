<?php

namespace Tests\Feature;

use App\Site;
use App\UserProgramTaskDocument;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProgramTaskDocumentTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->site = factory(Site::class)->create();

        $this->user->assignRole(1);
        $this->user->assignSite($this->site->id);
        $this->headers['siteid'] = $this->site->id;

        factory(UserProgramTaskDocument::class)->create([
            'site_id' =>  $this->site->id
        ]);

        $this->payload = [
            'user_program_task_id' =>  1,
            'description' =>  'Description',
        ];
    }

    /** @test */
    function it_requires_following_details()
    {
        $this->json('post', '/api/user_program_task_documents', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                "errors"  =>  [
                    "user_program_task_id"        =>  ["The user program task id field is required."],
                ],
                "message" =>  "The given data was invalid."
            ]);
    }

    /** @test */
    function add_new_UserProgramTaskDocument()
    {
        $this->json('post', '/api/user_program_task_documents', $this->payload, $this->headers)
            ->assertStatus(201)
            ->assertJson([
                'data'   => [
                    'user_program_task_id' =>  1,
                    'description' =>  'Description',
                ]
            ])
            ->assertJsonStructureExact([
                'data'   => [
                    'user_program_task_id',
                    'description',
                    'site_id',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    /** @test */
    function list_of_userProgramtaskDocuments()
    {
        $this->disableEH();
        $this->json('GET', '/api/user_program_task_documents', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    0 =>  [
                        'user_program_task_id',
                        'description',
                    ]
                ]
            ]);
        $this->assertCount(1, UserProgramTaskDocument::all());
    }

    /** @test */
    function show_single_UserProgram()
    {

        $this->json('get', "/api/user_program_task_documents/1", [], $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'user_program_task_id' =>  1,
                    'description' =>  'Description',
                ]
            ]);
    }

    /** @test */
    function update_single_UserProgramTaskDocument()
    {
        $payload = [
            'user_program_task_id' =>  2,
            'description' =>  'Description Updated',
        ];

        $this->json('patch', '/api/user_program_task_documents/1', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJson([
                'data'    => [
                    'user_program_task_id' =>  2,
                    'description' =>  'Description Updated',
                ]
            ])
            ->assertJsonStructureExact([
                'data'  => [
                    'id',
                    'site_id',
                    'user_program_task_id',
                    'document_path',
                    'description',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    /** @test */
    function delete_UserProgram()
    {
        $this->disableEH();
        $this->json('delete', '/api/user_program_task_documents/1', [], $this->headers)
            ->assertStatus(204);

        $this->assertCount(0, UserProgramTaskDocument::all());
    }
}
