<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;

class UsersProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // SUCCESS CASE
    public function user_profile_authenticated_user_success()
    {
        $user = User::factory()->create();

        Passport::actingAs($user, 'api');

        $this->get('/api/profile')
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User Profile',
                'data' => [
                    "name" => $user->name,
                    "email" => $user->email,
                ]
            ]);
    }

    public function user_profile_unauthenticated_user_error()
    {

        $response = $this->getJson('/api/profile');

        print_r(json_encode($response));

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }
}
