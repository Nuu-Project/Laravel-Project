<?php

namespace Tests\Feature\Web\Admin;

use App\Enums\RoleType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 確保角色存在
        Role::create(['name' => RoleType::Admin->value()]);
    }

    public function test_index_displays_users_with_filters()
    {
        $user1 = User::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
        $user2 = User::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com']);
        $admin = User::factory()->hasAdmin()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['filter[name]' => 'Alice']));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users', function ($users) use ($user1) {
            return $users->contains($user1) && ! $users->contains(User::where('name', 'Bob')->first());
        });
    }

    public function test_active_user()
    {
        $user = User::factory()->create(['time_limit' => null]);
        $admin = User::factory()->hasAdmin()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.active', $user));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', "用戶{$user->name}重新啟用！");
        $this->assertNotNull($user->fresh()->time_limit);
    }
}
