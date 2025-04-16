<?php

namespace Tests\Feature\Web\User;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class ProductMessageControllerTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();

        $this->product = Product::factory()->create();
    }

    public function test_authenticated_user_can_store_a_message_for_a_product(): void
    {

        $data = $this->getDate();

        $this->post(route('user.products.messages.store', $this->product), $data)
            ->assertRedirect(route('products.show', $this->product));

        $this->assertDatabaseHas('messages', $data + [
            'user_id' => auth()->id(),
            'product_id' => $this->product->id,
        ]);
    }

    public function test_guest_cannot_store_a_message_for_a_product(): void
    {
        $this->logout();

        $data = $this->getDate();

        $this->post(route('user.products.messages.store', $this->product), $data)
            ->assertRedirect('/login');

        $this->assertDatabaseEmpty(Message::class);
    }

    public function test_message_field_is_required_when_storing_a_message(): void
    {
        $this->post(route('user.products.messages.store', $this->product))
            ->assertInvalid('message');

        $this->assertDatabaseEmpty(Message::class);
    }

    public function test_authenticated_user_can_view_the_edit_form_for_their_own_message(): void
    {
        $message = $this->createMessage();

        $this->get(route('user.products.messages.edit', [$this->product, $message]))
            ->assertOk()
            ->assertViewIs('messages.edit')
            ->assertViewHas('message', $message)
            ->assertViewHas('productId', $this->product);
    }

    public function test_authenticated_user_cannot_view_the_edit_form_for_another_users_message(): void
    {
        $otherUser = User::factory()->create();

        $message = $this->createMessage([
            'user_id' => $otherUser->id,
        ]);

        $this->get(route('user.products.messages.edit', [$this->product, $message]))
            ->assertForbidden();
    }

    public function test_guest_cannot_view_the_edit_form_for_a_message(): void
    {
        $this->logout();

        $message = $this->createMessage();

        $this->get(route('user.products.messages.edit', [$this->product, $message]))
            ->assertRedirect('/login');
    }

    public function test_authenticated_user_can_update_their_own_message(): void
    {
        $message = $this->createMessage([
            'message' => 'Original message'
        ]);
        $data = $this->getDate();

        $this->put(route('user.products.messages.update', [$this->product, $message]), $data)
            ->assertRedirect(route('products.show', $this->product));

        $this->assertDatabaseHas('messages', $data + [
            'id' => $message->id,
        ]);
    }

    public function test_authenticated_user_cannot_update_another_users_message(): void
    {
        $otherUser = User::factory()->create();

        $message = $this->createMessage([
            'user_id' => $otherUser->id,
            'message' => 'Original message'
        ]);
        $data = $this->getDate();

        $this->put(route('user.products.messages.update', [$this->product, $message]), $data)
            ->assertForbidden();

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'message' => $message->message,
        ]);
    }

    public function test_guest_cannot_update_a_message(): void
    {
        $this->logout();

        $message = $this->createMessage([
            'message' => 'Original message'
        ]);
        $data = $this->getDate();

        $this->put(route('user.products.messages.update', [$this->product, $message]), $data)
            ->assertRedirect('/login');

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'message' => $message->message,
        ]);
    }

    public function test_message_field_is_required_when_updating_a_message(): void
    {
        $message = $this->createMessage([
            'message' => 'Original message'
        ]);

        $this->put(route('user.products.messages.update', [$this->product, $message]))
            ->assertInvalid('message');

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'message' => $message->message,
        ]);
    }

    public function test_authenticated_user_can_delete_their_own_message(): void
    {
        $message = $this->createMessage();

        $this->delete(route('user.products.messages.destroy', [$this->product, $message]))
            ->assertRedirect(route('products.show', $this->product))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('messages', ['id' => $message->id]);
    }

    public function test_authenticated_user_cannot_delete_another_users_message(): void
    {
        $otherUser = User::factory()->create();

        $message = $this->createMessage([
            'user_id' => $otherUser->id,
        ]);

        $this->delete(route('user.products.messages.destroy', [$this->product, $message]))
            ->assertForbidden();

        $this->assertDatabaseHas('messages', ['id' => $message->id]);
    }

    public function test_guest_cannot_delete_a_message(): void
    {
        $this->logout();

        $message = $this->createMessage();

        $this->delete(route('user.products.messages.destroy', [$this->product, $message]))
            ->assertRedirect('/login');

        $this->assertDatabaseHas('messages', ['id' => $message->id]);
    }

    public function test_authenticated_user_can_reply_to_a_message(): void
    {
        $message = $this->createMessage();
        $data = $this->getDate();

        $this->post(route('user.products.messages.reply', [$this->product, $message]), $data)
            ->assertRedirect(route('products.show', $this->product));

        $this->assertDatabaseHas('messages', $data + [
            'user_id' => auth()->id(),
            'product_id' => $this->product->id,
            'reply_to_id' => $message->id,
        ]);
    }

    public function test_guest_cannot_reply_to_a_message(): void
    {
        $this->logout();

        $message = $this->createMessage();
        $data = $this->getDate();

        $this->post(route('user.products.messages.reply', [$this->product, $message]), $data)
            ->assertRedirect('/login');

        $this->assertDatabaseCount('messages', 1);
    }

    public function test_message_field_is_required_when_replying_to_a_message(): void
    {
        $message = $this->createMessage();

        $this->post(route('user.products.messages.reply', [$this->product, $message]))
            ->assertInvalid('message');

        $this->assertDatabaseCount('messages', 1);
    }

    private function getDate(): array
    {
        return [
            'message' => fake()->sentence(),
        ];
    }

    private function createMessage(array $state = []): Message
    {
        return Message::factory()
            ->state($state + [
                'user_id' => auth()->id() ?? User::factory(),
                'product_id' => $this->product->id,
            ])
            ->create();
    }
}
