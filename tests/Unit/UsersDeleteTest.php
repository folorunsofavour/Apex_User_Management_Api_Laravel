<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;

class UsersDeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // SUCCESS CASE
    public function admin_role_user_delete_success()
    {
        $admin = User::factory()->create(['roles' => 'admin']);
        $user = User::factory()->create();

        Passport::actingAs($admin);

        $response = $this->deleteJson('/api/delete_user/' . $user->id);
        
        $response->assertStatus(200)
        ->assertJson([
            'status' => true,
            'message' => 'User Deleted Succesfully',
        ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    // ERROR CASES
    /** @test */
    public function user_role_delete_error()
    {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->deleteJson('/api/delete_user/' . $user->id);
        

        $response->assertStatus(403)
            ->assertJson([
                'status' => false,
                'message' => 'User not Authorized to perform this Operation',
            ]);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /** @test */
    public function admin_role_delete_non_user()
    {
        $admin = User::factory()->create(['roles' => 'admin']);

        Passport::actingAs($admin);

        $response = $this->deleteJson('/api/delete_user/999');
    
        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'User Not Found',
            ]);
    }


}
