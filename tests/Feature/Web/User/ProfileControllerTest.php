<?php

namespace Tests\Feature\Web\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsUser();
    }

    public function test_profile_edit_page_are_accessible_by_authenticated_user(): void
    {
        $this->get(route('user.profile.edit'))
            ->assertOk()
            ->assertViewIs('profile.edit')
            ->assertViewHas('user', auth()->user());
    }

    public function test_profile_update_password_succeeds(): void
    {
        $data = $this->getData();

        $this->patch(route('user.profile.update'), $data)
            ->assertRedirect(route('user.profile.edit'))
            ->assertSessionHas('status', 'profile-updated');

        $user = auth()->user()->fresh();

        $this->assertTrue(Hash::check($data['password'], $user->password));
    }

    public function test_profile_update_fails_when_user_is_not_authenticated(): void
    {
        $this->logout();

        $data = $this->getData();

        $this->patch(route('user.profile.update'), $data)
            ->assertRedirect('/login');
    }

    private function getData(): array
    {
        return [
            'password' => 'NewSecretPassword123',
            'password_confirmation' => 'NewSecretPassword123',
        ];
    }
}
