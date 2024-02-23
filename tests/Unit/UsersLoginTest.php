<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsersLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // SUCCESS CASE
    public function user_login_valid_data_success()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com', 
            'password' => Hash::make('secret123')
        ]);

        $this->post('/api/login', [
            'email' => 'john@example.com',
            'password' => 'secret123',
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'token',
            ])
            ->assertJson([
                'status' => true,
                'message' => 'User logged in successfully',
            ]);

        // Check if token is valid
        $this->assertAuthenticated();
    }

    // ERROR CASES
    // ERROR FOR EMAIL FIELD
    public function user_login_missing_email_error()
    {
        $this->post('/api/login', [
            'password' => 'secret123',
        ])
            ->assertStatus(422)
            ->assertJson([
                'data' => [
                    'email' => ['The email field is required.'],
                ]
                
            ]);
    }

    public function user_login_invalid_email_error()
    {
        $this->post('/api/login', [
            'email' => 'invalid-email',
            'password' => 'secret123',
        ])
            ->assertStatus(422)
            ->assertJson([
                'data' => [
                    'email' => ['The email must be a valid email address.'],
                ]
                
            ]);
    }

    // ERROR FOR PASSWORD FIELD
    public function user_login_missing_password_error()
    {
        $this->post('/api/login', [
            'email' => 'john@example.com',
        ])
            ->assertStatus(422)
            ->assertJson([
                'data' => [
                    'password' => ['The password field is required.'],
                ]
                
            ]);
    }

    public function user_login_incorrect_credentials_error()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com', 
            'password' => Hash::make('secret123')
        ]);

        $this->post('/api/login', [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ])
            ->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid Login details',
            ]);
    }

    // public function user_login_nonexistent_user_error()
    // {
    //     $this->post('/api/login', [
    //         'email' => 'non-existent@example.com',
    //         'password' => 'secret123',
    //     ])
    //         ->assertStatus(401)
    //         ->assertJson([
    //             'status' => false,
    //             'message' => 'Non-Existent User',
    //         ]);
    // }
}
