<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Site;
use App\Value;

class ValueTest extends TestCase
{
  use DatabaseTransactions;
  
  public function setUp()
  {
    parent::setUp();

    $this->site = factory(Site::class)->create();

    $this->user->assignRole(1);
    $this->user->assignSite($this->site->id);
    $this->headers['siteid'] = $this->site->id;  

    factory(Value::class)->create([
      'site_id' =>  $this->site->id
    ]);

    $this->payload = [
      'name'  =>  'Value 2',
    ];
  }

  /** @test */
  function it_requires_following_details()
  {
    $this->json('post', '/api/values', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
          "errors"  =>  [
            "name"        =>  ["The name field is required."],
          ],
          "message" =>  "The given data was invalid."
        ]);
  }

  /** @test */
  function add_new_value()
  {
    $this->json('post', '/api/values', $this->payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
          'data'   =>[
            'name'  =>  'Value 2',
          ]
        ])
      ->assertJsonStructureExact([
          'data'   => [
            'name',
            'site_id',
            'updated_at',
            'created_at',
            'id'
          ]
        ]);
  }

  /** @test */
  function list_of_values()
  {
    $this->disableEH();
    $this->json('GET', '/api/values',[], $this->headers)
      ->assertStatus(200)
      ->assertJsonStructure([
          'data' => [
            0 =>  [
              'name'
            ] 
          ]
        ]);
    $this->assertCount(2, Value::all());
  }

  /** @test */
  function show_single_value()
  {

    $this->json('get', "/api/values/1", [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
          'data'  => [
            'name'  =>  'STATE',
          ]
        ]);
  }

  /** @test */
  function update_single_value()
  {
    $payload = [ 
      'name'  =>  'Value 1 Updated',
    ];

    $this->json('patch', '/api/values/1', $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
          'data'    => [
            'name'  =>  'Value 1 Updated',
          ]
       ])
      ->assertJsonStructureExact([
          'data'  => [
            'id',
            'site_id',
            'name',
            'created_at',
            'updated_at',
          ]
      ]);
  }

  /** @test */
  function delete_value()
  {
    $this->json('delete', '/api/values/1', [], $this->headers)
      ->assertStatus(204);

    $this->assertCount(1, Value::all());
  }
}
