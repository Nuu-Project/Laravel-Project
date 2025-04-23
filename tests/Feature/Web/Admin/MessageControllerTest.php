<?php

namespace Tests\Feature\Web\Admin;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin();
    }

    public function test_admin_can_view_messages_page()
    {
        $admin = User::factory()->create()->assignRole('admin');

        $this->actingAs($admin)
            ->get(route('admin.messages.index'))
            ->assertStatus(200);
    }

    public function test_admin_can_see_messages_on_index_page()
    {
        $admin = User::factory()->create()->assignRole('admin');
        $message = Message::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.messages.index'))
            ->assertViewIs('admin.messages.index')
            ->assertSee($message->content);
    }

    public function test_admin_can_filter_messages_by_user_name()
    {
        $user1 = User::factory()->create(['name' => 'Alice']);
        $user2 = User::factory()->create(['name' => 'Bob']);
        $admin = User::factory()->create()->assignRole('admin');

        $message1 = Message::factory()->create(['user_id' => $user1->id]);
        $message2 = Message::factory()->create(['user_id' => $user2->id]);

        $this->actingAs($admin)
            ->get(route('admin.messages.index', ['filter[name]' => 'Alice']))
            ->assertSuccessful()
            ->assertSee($message1->content)
            ->assertDontSee($message2->content);
    }

    public function test_guest_cannot_access_messages_page()
    {
        $this->logout();

        $this->get(route('admin.messages.index'))
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_user_cannot_access_messages_page()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('admin.messages.index'))
            ->assertForbidden();
    }

    public function test_messages_are_paginated()
    {
        $admin = User::factory()->create()->assignRole('admin');
        Message::factory()->count(25)->create();

        $this->actingAs($admin)
            ->get(route('admin.messages.index'))
            ->assertViewHas('messages', function ($paginator) {
                return $paginator->count() === 10; // 假設每頁 10 筆
            });
    }

    public function test_invalid_filter_parameter_does_not_break_page()
    {
        $admin = User::factory()->create()->assignRole('admin');

        $this->actingAs($admin)
            ->get(route('admin.messages.index', ['filter[invalid]' => 'test']))
            ->assertStatus(400); // 改為預期回傳 400 Bad Request
    }
}
