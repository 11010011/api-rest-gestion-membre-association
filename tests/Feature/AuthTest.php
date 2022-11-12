<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase
{
  use RefreshDatabase;
  /**
   * feature test register without email.
   *
   * @return void
   */
  public function test_register_empty_email()
  {
    $response = $this->postJson('api/auth/register', [
      'name' => 'User admin',
      'password' => '00000000',
      'password_confirmation' => '00000000'
    ]);

    $response->assertJson(fn (AssertableJson $json) =>
      $json->has('message')
        ->has('errors', fn ($json) => 
          $json->where('email', [
              0 => "The email field is required."
            ]
          )
        )
    );
  }

  /**
   * feature test register without confirmation password.
   *
   * @return void
   */
  public function test_register_password_not_confirmed()
  {
    $response = $this->postJson('api/auth/register', [
      'name' => 'User admin',
      'email' => 'seirankurimi@gmail.com',
      'password' => '00000000',
    ]);

    
    $response->assertJson(fn (AssertableJson $json) =>
      $json->has('message')
        ->has('errors', fn ($json) =>
          $json->where('password',  [
              0 => "The password confirmation does not match."
            ]
          )
        )
    );
  }

  /**
   * feature test register success
   *
   * @return void
   */
  public function test_register_success()
  {
    $response = $this->postJson('api/auth/register', [
      'name' => 'User admin',
      'email' => 'seirankurimi@gmail.com',
      'password' => '00000000',
      'password_confirmation' => '00000000'
    ]);
    
    $response->assertJson(fn (AssertableJson $json) =>
      $json->has('data', fn ($json) =>
          $json->where('name', 'User admin')
              ->where('email', 'seirankurimi@gmail.com')
              ->etc()
        )
    );
  }

  /**
   * feature test login credential error
   *
   * @return void
   */
  public function test_login_credential_errors()
  {
    $this->postJson('api/auth/register', [
      'name' => 'User admin',
      'email' => 'seirankurimi@gmail.com',
      'password' => '00000000',
      'password_confirmation' => '00000000'
    ]);

    $response = $this->postJson('api/auth/login', [
      'email' => 'seirankurimi@gmail.com',
      'password' => '0000000',
    ]);
    
    $response->assertJson(fn (AssertableJson $json) =>
      $json->has('errors')->etc()
    );
  }

  /**
   * feature test login credential success
   *
   * @return void
   */
  public function test_login_credential_success()
  {
    $this->postJson('api/auth/register', [
      'name' => 'User admin',
      'email' => 'seirankurimi@gmail.com',
      'password' => '00000000',
      'password_confirmation' => '00000000'
    ]);

    $response = $this->postJson('api/auth/login', [
      'email' => 'seirankurimi@gmail.com',
      'password' => '00000000',
    ]);
    
    $response->assertJson(fn (AssertableJson $json) =>
      $json->has('data', fn ($json) =>
        $json->has('user')
          ->has('token')
          ->etc()
      )
        ->etc()
    );
  }
}
