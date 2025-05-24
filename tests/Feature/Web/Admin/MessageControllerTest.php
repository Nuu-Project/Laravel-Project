<?php

namespace Tests\Feature\Web\Admin;

use App\Models\Message;
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
        $this->get(route('admin.messages.index'))
            ->assertOk();
    }

    public function test_admin_can_see_messages_on_index_page()
    {
        $message = $this->createMessage();

        $this->get(route('admin.messages.index'))
            ->assertOk()
            ->assertViewIs('admin.messages.index')
            ->assertSee($message->content);
    }

    public function test_admin_can_filter_messages_by_user_name()
    {
        $user1 = $this->createUser([
            'name' => 'Alice',
        ]);

        $user2 = $this->createUser([
            'name' => 'Bob',
        ]);

        $message1 = $this->createMessage([
            'user_id' => $user1->id,
        ]);

        $message2 = $this->createMessage([
            'user_id' => $user2->id,
        ]);

        $this->get(route('admin.messages.index', [
            'filter[name]' => 'Alice',
        ]))
            ->assertOk()
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
        $user = $this->createUser([
            'name' => 'John Doe',
        ]);

        $this->actingAs($user)
            ->get(route('admin.messages.index'))
            ->assertForbidden();
    }

    public function test_messages_are_paginated()
    {
        Message::factory()->count(25)->create();

        $this->get(route('admin.messages.index'))
            ->assertViewHas('messages', function ($paginator) {
                return $paginator->count() === 10;
            });
    }

    public function test_invalid_filter_parameter_does_not_break_page()
    {
        $this->get(route('admin.messages.index', [
            'filter[invalid]' => 'test',
        ]))->assertBadRequest();
    }

    public function createMessage(array $state = []): Message
    {
        return Message::factory()->state($state)->create();
    }
}
