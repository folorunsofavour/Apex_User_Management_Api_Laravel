<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Laravel\Passport\Passport;

class UsersUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // SUCCESS CASE
    public function user_update_valid_data_success()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('secret123'),
        ]);

        Passport::actingAs($user); // Authenticate as the user

        $updatedData = [
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ];

        $response = $this->putJson('/api/update', $updatedData);

        $response->assertStatus(200)
        ->assertJson([
            'status' => true,
            'message' => 'User Details Updated Succesfully',
            'data' => [
                'name' => 'Jane Doe',
                'email' => 'janedoe@example.com',
            ],
        ]);

        // Assert user data is updated in the database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
        ]);

        // Check if password is hashed correctly
        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }


    // ERROR CASE
    /** @test */
    public function user_update_missing_data_error()
    {
        $user = User::factory()->create();

        Passport::actingAs($user);

        
        $response = $this->putJson('/api/update', []);

        $response->assertStatus(422)
            ->assertJson([
                'data' => [
                    "name"=> [
                        "The name field is required."
                    ],
                    "email"=> [
                        "The email field is required."
                    ],
                    "password"=> [
                        "The password field is required."
                    ]
                ]
                
            ]);
    }

    // /** @test */
    public function user_update_invalid_email_error()
    {
        $user = User::factory()->create();
        
        Passport::actingAs($user);

        $response = $this->putJson('/api/update', ['email' => 'invalid-email']);

        $response->assertStatus(422)
            ->assertJson([
                'data' => [
                    'email' => ['The email must be a valid email address.'],
                ]    
            ]);
    }

    /** @test */
    public function user_update_unauthenticated_user_error()
    {
        $response = $this->putJson('/api/update', []);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

}
