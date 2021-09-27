<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SiteUserTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function it_requires_following_fields()
  {
    $this->json('post', '/api/site_user', [], $this->headers)
         ->assertStatus(422)
         ->assertExactJson([
            "errors"            =>  [
              "site_id" =>  ["The site id field is required."],
              "user_id"    =>  ["The user id field is required."]
            ],
            "message" =>  "The given data was invalid."
        ]);
  }

  /** @test */
  function assign_site()
  {
    $userTwo = factory(\App\User::class)->create();
    $site = factory(\App\Site::class)->create();
    $userTwo->assignSite($site->id);
    $check = $userTwo->hasSite($site->id);
    $this->assertTrue($check);
  }

  /** @test */
  function assign_site_to_user()
  {
    $this->disableEH();
    $userTwo = factory(\App\User::class)->create();
    $site = factory(\App\Site::class)->create();
    $this->payload      = [ 
      'user_id'    => $userTwo->id,
      'site_id' => $site->id
    ];
    $this->json('post', '/api/site_user', $this->payload , $this->headers)
      ->assertStatus(201)
      ->assertJson([
            'data'  =>  [
              'first_name' =>  $userTwo->first_name,
              'email'     =>  $userTwo->email,
              'sites'     =>  [
                0 =>  [
                  'name'  =>  $site->name
                ]
              ]
            ]
          ])
        ->assertJsonStructureExact([
          'data'  =>  [
            'id',
            'first_name',
            'middle_name',
            'last_name',
            'user_name',
            'gender',
            'dob',
            'email',
            'api_token',
            'active',
            'relation_id',
            'relation_with_id',
            'image_path',
            'created_at',
            'updated_at',
            'sites',
          ],
          'success'
        ]);;;
  }
}
