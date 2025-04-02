<?php

namespace Tests\Feature\Web\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_page_is_accessible_by_authenticated_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
        $response->assertViewHas('user', $user);
    }

    public function test_update_profile_succeeds_with_valid_data()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $validData = ['name' => 'New Name', 'email' => 'test@example.com'];

        $response = $this->actingAs($user)->patch(route('user.profile.update'), $validData);

        $response->assertRedirect(route('user.profile.edit'));
        $response->assertSessionHas('status', 'profile-updated');
        $this->assertDatabaseHas('users', $validData);
    }

    public function test_update_profile_fails_when_user_is_not_authenticated()
    {
        $invalidData = ['name' => 'New Name'];

        $response = $this->patch(route('user.profile.update'), $invalidData);

        $response->assertRedirect('/login');
    }
}
