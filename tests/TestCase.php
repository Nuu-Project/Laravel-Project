<?php

namespace Tests;

use App\Enums\Tagtype;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function createBasicTags(): void
    {
        foreach (Tagtype::cases() as $type) {
            Tag::factory()->create(['type' => $type->value]);
        }
    }

    public function createUser(array $stase = []): User
    {
        return User::factory()
            ->state($stase + [
                'name' => 'Test User',
                'email' => 'U9999999@o365.nuu.edu.tw',
                'time_limit' => null,
            ])->create();
    }

    public function createAdmin(): User
    {
        return User::factory()->hasAdmin()->create();
    }

    public function actingAsUser(): User
    {
        $user = $this->createUser();
        $this->actingAs($user);

        return $user;
    }

    public function actingAsAdmin(): User
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        return $admin;
    }

    public function logout(): void
    {
        $this->post('/logout');

        $this->assertGuest();
    }
}
