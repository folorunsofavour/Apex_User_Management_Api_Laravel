<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class UsersRegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    // SUCCESS CASES
    public function user_reg_valid_data_success()
    {
        $this->post('/api/register', [
            'name' => 'John Doe',
            // 'email' => $this->faker->unique()->safeEmail,
            'email' => 'braden72@example.net',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])
            ->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'User Created Succesfully',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            // 'email' => $this->faker->unique()->safeEmail,
            'email' => 'braden72@example.net',
        ]);
    }

    /** @test */
    // ERROR CASES

    // ERROR FOR NAME FIELD
    public function user_reg_missing_name_error()
    {
        $this->post('/api/register', [
            'email' => 'braden72@example.net',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])
            ->assertStatus(422) // Unprocessable Entity
            ->assertJson([
                'data' => [
                    'name' => ['The name field is required.'],
                ]
            ]);
    }

    public function user_reg_invalid_name_error()
    {
        $this->post('/api/register', [
            'name' => 'John Doe123', // Contains invalid characters
            'email' => $this->faker->email,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])
            ->assertStatus(422)
            ->assertJson([
                'data' => [
                    'name' => ['The name format is invalid.'],
                ]
            ]);
    }


    // ERROR FOR EMAIL FIELD
    public function user_reg_missing_email_error()
    {
        $this->post('/api/register', [
            'name' => 'John Doe',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])
            ->assertStatus(422)
            ->assertJson([
                'data' => [
                    'email' => ['The email field is required.'],
                ]
                
            ]);
    }

    public function user_reg_invalid_email_error()
    {
        $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])
            ->assertStatus(422)
            ->assertJson([
                'data' => [
                    'email' => ['The email must be a valid email address.'],
                ]
                
            ]);
    }

    public function user_reg_duplicate_email_error()
    {
        $user = User::factory()->create(['email' => 'existing@example.com']);

        $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => $user->email,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])
            ->assertStatus(422)
            ->assertJson([
                'data' => [
                    'email' => ['The email has already been taken.'],
                ]
                
            ]);
    }

    
    // ERROR FOR PASSWORD FIELD
    public function user_reg_missing_password_error()
    {
        $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => $this->faker->email,
        ])
            ->assertStatus(422)
            ->assertJson([
                'data' => [
                    'password' => ['The password field is required.'],
                ]
                
            ]);
    }

    public function user_reg_short_password_error()
    {
        $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => $this->faker->email,
            'password' => 'short',
            'password_confirmation' => 'short',
        ])
            ->assertStatus(422)
            ->assertJson([
                'data' => [
                    'password' => ['The password must be at least 8 characters.'],
                ]
                
            ]);
    }

    public function user_reg_mismatched_password_error()
    {
        $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => $this->faker->email,
            'password' => 'secret123',
            'password_confirmation' => 'different',
        ])
            ->assertStatus(422)
            ->assertJson([
                'data' => [
                    'password' => ['The password confirmation does not match.'],
                ]
                
            ]);
    }
   
}
