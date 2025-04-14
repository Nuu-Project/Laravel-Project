<?php

namespace Tests;

use App\Enums\Tagtype;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function createBasicTags(): void
    {
        Tag::factory()->create(['type' => Tagtype::Grade->value]);
        Tag::factory()->create(['type' => Tagtype::Semester->value]);
        Tag::factory()->create(['type' => Tagtype::Subject->value]);
        Tag::factory()->create(['type' => Tagtype::Category->value]);
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
