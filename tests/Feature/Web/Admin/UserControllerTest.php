<?php

namespace Tests\Feature\Web\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
    }

    public function test_index_displays_users_with_filters(): void
    {
        $user1 = User::factory()->create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
        ]);
        $user2 = User::factory()->create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
        ]);

        $this->get(route('admin.users.index', ['filter[name]' => 'Alice']))
            ->assertOk()
            ->assertViewIs('admin.users.index')
            ->assertViewHas('users', function ($users) use ($user1) {
                return $users->contains($user1) && ! $users->contains(User::where('name', 'Bob')->first());
            });
    }

    public function test_active_user(): void
    {
        $user = User::factory()->create(['time_limit' => null]);

        $this->patch(route('admin.users.active', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', "用戶{$user->name}重新啟用！");

        $this->assertNotNull($user->fresh()->time_limit);
    }
}
