<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class UserTest extends TestCase
{
  use DatabaseTransactions;

  public function setUp()
  {
    parent::setUp();

    $this->site = factory(\App\Site::class)->create([
      'name' => 'test'
    ]);
    $this->user->assignSite($this->site->id);
    $this->headers['siteid'] = $this->site->id;

    $this->payload = [
      'first_name'   =>  'sangeetha',
      'middle_name'  =>  'middle',
      'last_name'   =>  'last',
      'user_name'   =>  'sangeetha',
      'email'       =>  'sangeetha@gmail.com',
      'active'      =>  1,
      'role_id'     =>  2,
      'site_id'     =>  $this->site->id,
    ];
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/users')
      ->assertStatus(401);
  }

  /** @test */
  function it_requires_following_details()
  {
    $payload = [];

    $this->json('post', '/api/users', $payload, $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "first_name"  =>  ["The first name field is required."],
          "email"      =>  ["The email field is required."],
          "active"     =>  ["The active field is required."],
          "role_id"    =>  ["The role id field is required."],
          "site_id"    =>  ["The site id field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]);
  }

  /** @test */
  function add_new_user()
  {
    $this->disableEH();
    $this->json('post', '/api/users', $this->payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'first_name'   =>  'sangeetha',
          'email'       =>  'sangeetha@gmail.com',
          'active'      =>  1,

        ]
      ])
      ->assertJsonStructure([
        'data'  =>  [
          'first_name',
        ]
      ])
      ->assertJsonStructureExact([
        'data'  =>  [
          'first_name',
          'middle_name',
          'last_name',
          'user_name',
          'email',
          'active',
          'updated_at',
          'created_at',
          'id',
          'roles',
          'sites',
        ],
        'success'
      ]);
  }

  /** @test */
  public function list_of_users()
  {
    $this->disableEH();
    $user = factory(\App\User::class)->create();
    $user->assignRole(3);
    $user->assignSite($this->site->id);

    $this->json('get', '/api/users?role_id=3', [], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
        'data' => []
      ]);

    $this->assertCount(1, User::whereHas('roles',  function ($q) {
      $q->where('name', '!=', 'Admin');
      $q->where('name', '!=', 'Super Admin');
    })->get());
  }

  /** @test */
  function show_single_user_details()
  {
    $this->disableEH();
    $this->json('get', "/api/users/1", [], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
        'data'  =>  [
          'first_name',
          'email'
        ]
      ]);
  }

  /** @test */
  function update_single_user_details()
  {
    $this->disableEH();
    $user = factory(\App\User::class)->create();
    // dd($user->id);
    // Old Edit + No Delete + 1 New
    $payload = [
      // 'id'          =>  $user->id,
      'first_name'   =>  'sangeetha 1',
      'email'       =>  'sangeetha@gmail.com',
      'active'      =>  1,
      'role_id'     =>  2,
      'site_id'     =>  1,
    ];
    $this->json('post', '/api/users', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'    =>  [
          // 'id'          =>  $user->id,
          'first_name'   =>  'sangeetha 1',
          'email'       =>  'sangeetha@gmail.com',
          'active'      =>  1,
        ],
      ])
      ->assertJsonStructureExact([
        'data'  => [
          'first_name',
          'email',
          'active',
          'updated_at',
          'created_at',
          'id',
          'roles',
          'sites',
        ],
        'success'
      ]);
  }

  /** @test */
  function update_single_user_details_2()
  {
    $this->disableEH();
    $user = factory(\App\User::class)->create();
    // 1 Delete + 1 New
    $payload1 = [
      // 'id'          =>  $user->id,
      'first_name'   =>  'sangeetha 1',
      'email'       =>  'sangeetha@gmail.com',
      'active'      =>  1,
      'role_id'     =>  2,
      'site_id'     =>  1,
    ];
    $this->json('post', '/api/users', $payload1, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'    =>  [
          // 'id'          =>  $user->id,
          'first_name'   =>  'sangeetha 1',
          'email'       =>  'sangeetha@gmail.com',
          'active'      =>  1,
        ],
      ])
      ->assertJsonStructureExact([
        'data'  => [
          'first_name',
          'email',
          'active',
          'updated_at',
          'created_at',
          'id',
          'roles',
          'sites',
        ],
        'success'
      ]);
  }
}
