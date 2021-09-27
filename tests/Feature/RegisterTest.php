<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  public function registration_requires_following_fields()
  {
    $this->json('POST','/api/register')
      ->assertStatus(422)
      ->assertExactJson([
          "errors"  =>  [
            "first_name"               =>  ["The first name field is required."],
            "email"                   =>  ["The email field is required."],
            "password"                =>  ["The password field is required."],
          ],
          "message" =>  "The given data was invalid."
        ]);
  }

  /** @test */
  function user_registered_successfully()
  {
    $this->disableEH();

    $userDetails = [
      'first_name'            =>'sangeetha',
      'email'                =>'sangeetha@gmail.com',
      'password'             =>'behappy',
      'password_confirmation'=>'behappy'
    ];

    $this->json('POST','/api/register', $userDetails)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'first_name'            =>'sangeetha',
          'email'                =>'sangeetha@gmail.com',
        ]
      ])
      ->assertJsonStructure([
          'data'  =>  [
            'first_name',
            'email',
            'api_token'
          ]
        ])
      ->assertJsonStructureExact([
          'data'  =>  [
            'first_name', 
            'email',
            'active',
            'updated_at',
            'created_at',
            'id',
            'api_token'
          ],
          'message',
          'token',
          'success'
        ]);
  }
}
