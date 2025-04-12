<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function createUser(): User
    {
        return User::factory()->create();
    }

    public function createAdmin(): User
    {
        return User::factory()->hasAdmin()->create();
    }

    public function actingAsUser(): void
    {
        $this->actingAs($this->createUser());
    }

    public function actingAsAdmin(): void
    {
        $this->actingAs($this->createAdmin());
    }

    public function logout(): void
    {
        $this->post('/logout');

        $this->assertGuest();
    }
}
