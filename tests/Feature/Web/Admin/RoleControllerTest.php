<?php

namespace Tests\Feature\Web\Admin;

use App\Enums\RoleType;
use App\Models\User;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    private $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = $this->actingAsAdmin();
    }

    public function test_index_view_is_accessible_and_displays_admin_users()
    {
        $this->get(route('admin.roles.index'))
            ->assertOk()
            ->assertViewIs('admin.roles.index')
            ->assertViewHas('users');
    }

    public function test_create_view_lists_users_without_roles()
    {
        $this->get(route('admin.roles.create'))
            ->assertOk()
            ->assertViewIs('admin.roles.create')
            ->assertViewHas('users');
    }

    public function test_create_view_filters_users_by_name_or_email()
    {
        $matching = $this->createUser([
            'name' => 'John Doe',
            'email' => 'buud@gamil.com',
        ]);
        $nonMatching = $this->createUser([
            'name' => 'Jane Doe',
            'email' => 'uu@gamil.com',
        ]);

        $matching->roles()->detach();
        $nonMatching->roles()->detach();

        $this->get(route('admin.roles.create', ['filter' => ['name' => 'john']]))
            ->assertOk()
            ->assertViewHas('users', fn ($users) => $users->contains($matching) && ! $users->contains($nonMatching));
    }

    public function test_roles_can_be_assigned_to_users()
    {
        $users = User::factory()->count(2)->create();

        $this->post(route('admin.roles.store'), ['user_ids' => $users->pluck('id')->toArray()])
            ->assertRedirect(route('admin.roles.index'))
            ->assertSessionHas('success');

        foreach ($users as $user) {
            $this->assertTrue($user->fresh()->hasRole(RoleType::Admin->value()));
        }
    }

    public function test_assign_validation_fails_without_user_ids()
    {
        $this->post(route('admin.roles.store'), [])
            ->assertRedirect()
            ->assertSessionHasErrors('user_ids');
    }

    public function test_assign_validation_fails_with_non_array_user_ids()
    {
        $this->post(route('admin.roles.store'), ['user_ids' => 'string'])
            ->assertRedirect()
            ->assertSessionHasErrors('user_ids');
    }

    public function test_assign_validation_fails_with_invalid_user_ids()
    {
        $valid = $this->createUser();
        $invalidId = 999999;

        $this->post(route('admin.roles.store'), ['user_ids' => [$valid->id, $invalidId]])
            ->assertRedirect()
            ->assertSessionHasErrors('user_ids.*');
    }

    public function test_roles_can_be_removed_from_users()
    {
        $target = $this->createAdmin();

        $this->put(route('admin.roles.update', ['role' => 'admin']), ['selected_ids' => [$target->id]])
            ->assertRedirect(route('admin.roles.index'))
            ->assertSessionHas('success');

        $this->assertFalse($target->fresh()->hasRole('admin'));
    }

    public function test_remove_validation_fails_without_selected_ids()
    {
        $this->put(route('admin.roles.update', ['role' => 'admin']), [])
            ->assertRedirect()
            ->assertSessionHasErrors('selected_ids');
    }

    public function test_remove_validation_fails_with_non_array_selected_ids()
    {
        $this->put(route('admin.roles.update', ['role' => 'admin']), ['selected_ids' => 'string'])
            ->assertRedirect()
            ->assertSessionHasErrors('selected_ids');
    }

    public function test_remove_validation_fails_with_invalid_selected_ids()
    {
        $valid = $this->createUser();
        $invalidId = 999999;

        $this->put(route('admin.roles.update', ['role' => 'admin']), ['selected_ids' => [$valid->id, $invalidId]])
            ->assertRedirect()
            ->assertSessionHasErrors('selected_ids.*');
    }

    public function test_admin_cannot_remove_own_role()
    {
        $this->put(route('admin.roles.update', ['role' => 'admin']), ['selected_ids' => [$this->adminUser->id]])
            ->assertRedirect(route('admin.roles.index'));

        $this->assertTrue($this->adminUser->fresh()->hasRole(RoleType::Admin));
    }

    public function test_removing_role_from_user_without_role_succeeds()
    {
        $user = $this->createUser();

        $this->assertFalse($user->hasRole('admin'));

        $this->put(route('admin.roles.update', ['role' => 'admin']), ['selected_ids' => [$user->id]])
            ->assertRedirect(route('admin.roles.index'))
            ->assertSessionHas('success');

        $this->assertFalse($user->fresh()->hasRole('admin'));
    }
}
