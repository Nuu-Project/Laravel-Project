<?php

namespace Tests\Feature\Web\User;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductMessageControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();
    }

    public function test_authenticated_user_can_store_a_message_for_a_product()
    {

        $product = Product::factory()->create();
        $messageText = $this->faker->sentence;

        $response = $this
            ->post(route('user.products.messages.store', $product), [
                'message' => $messageText,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $this->assertDatabaseHas('messages', [
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'message' => $messageText,
        ]);
    }

    public function test_guest_cannot_store_a_message_for_a_product()
    {
        $this->logout();
        $product = Product::factory()->create();
        $messageText = $this->faker->sentence;

        $response = $this->post(route('user.products.messages.store', $product), [
            'message' => $messageText,
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('messages', [
            'product_id' => $product->id,
            'message' => $messageText,
        ]);
    }

    public function test_message_field_is_required_when_storing_a_message()
    {

        $product = Product::factory()->create();

        $response = $this
            ->post(route('user.products.messages.store', $product), [
                'message' => '',
            ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseMissing('messages', [
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);
    }

    public function test_authenticated_user_can_view_the_edit_form_for_their_own_message()
    {

        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => auth()->id(), 'product_id' => $product->id]);

        $response = $this
            ->get(route('user.products.messages.edit', [$product, $message]));

        $response->assertOk();
        $response->assertViewIs('messages.edit');
        $response->assertViewHas('message', $message);
        $response->assertViewHas('productId', $product);
    }

    public function test_authenticated_user_cannot_view_the_edit_form_for_another_users_message()
    {

        $otherUser = User::factory()->create();
        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => $otherUser->id, 'product_id' => $product->id]);

        $response = $this
            ->get(route('user.products.messages.edit', [$product, $message]));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_view_the_edit_form_for_a_message()
    {
        $this->logout();

        $product = Product::factory()->create();
        $message = Message::factory()->create(['product_id' => $product->id]);

        $response = $this->get(route('user.products.messages.edit', [$product, $message]));

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_update_their_own_message()
    {

        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => auth()->id(), 'product_id' => $product->id, 'message' => 'Original message']);
        $updatedMessageText = $this->faker->sentence;

        $response = $this
            ->put(route('user.products.messages.update', [$product, $message]), [
                'message' => $updatedMessageText,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'message' => $updatedMessageText,
        ]);
    }

    public function test_authenticated_user_cannot_update_another_users_message()
    {

        $otherUser = User::factory()->create();
        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => $otherUser->id, 'product_id' => $product->id, 'message' => 'Original message']);
        $updatedMessageText = $this->faker->sentence;

        $response = $this
            ->put(route('user.products.messages.update', [$product, $message]), [
                'message' => $updatedMessageText,
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'message' => 'Original message',
        ]);
    }

    public function test_guest_cannot_update_a_message()
    {
        $this->logout();

        $product = Product::factory()->create();
        $message = Message::factory()->create(['product_id' => $product->id, 'message' => 'Original message']);
        $updatedMessageText = $this->faker->sentence;

        $response = $this->put(route('user.products.messages.update', [$product, $message]), [
            'message' => $updatedMessageText,
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'message' => 'Original message',
        ]);
    }

    public function test_message_field_is_required_when_updating_a_message()
    {

        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => auth()->id(), 'product_id' => $product->id, 'message' => 'Original message']);

        $response = $this
            ->put(route('user.products.messages.update', [$product, $message]), [
                'message' => '',
            ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'message' => 'Original message',
        ]);
    }

    public function test_authenticated_user_can_delete_their_own_message()
    {

        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => auth()->id(), 'product_id' => $product->id]);

        $response = $this
            ->delete(route('user.products.messages.destroy', [$product, $message]));

        $response->assertRedirect(route('products.show', $product));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    public function test_authenticated_user_cannot_delete_another_users_message()
    {

        $otherUser = User::factory()->create();
        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => $otherUser->id, 'product_id' => $product->id]);

        $response = $this
            ->delete(route('user.products.messages.destroy', [$product, $message]));

        $response->assertStatus(403);
        $this->assertDatabaseHas('messages', ['id' => $message->id]);
    }

    public function test_guest_cannot_delete_a_message()
    {
        $this->logout();

        $product = Product::factory()->create();
        $message = Message::factory()->create(['product_id' => $product->id]);

        $response = $this->delete(route('user.products.messages.destroy', [$product, $message]));

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('messages', ['id' => $message->id]);
    }

    public function test_authenticated_user_can_reply_to_a_message()
    {

        $product = Product::factory()->create();
        $parentMessage = Message::factory()->create(['product_id' => $product->id]);
        $replyText = $this->faker->sentence;

        $response = $this
            ->post(route('user.products.messages.reply', [$product, $parentMessage]), [
                'message' => $replyText,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $this->assertDatabaseHas('messages', [
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'message' => $replyText,
            'reply_to_id' => $parentMessage->id,
        ]);
    }

    public function test_guest_cannot_reply_to_a_message()
    {
        $this->logout();

        $product = Product::factory()->create();
        $parentMessage = Message::factory()->create(['product_id' => $product->id]);
        $replyText = $this->faker->sentence;

        $response = $this->post(route('user.products.messages.reply', [$product, $parentMessage]), [
            'message' => $replyText,
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('messages', [
            'product_id' => $product->id,
            'message' => $replyText,
            'reply_to_id' => $parentMessage->id,
        ]);
    }

    public function test_message_field_is_required_when_replying_to_a_message()
    {

        $product = Product::factory()->create();
        $parentMessage = Message::factory()->create(['product_id' => $product->id]);

        $response = $this
            ->post(route('user.products.messages.reply', [$product, $parentMessage]), [
                'message' => '',
            ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseMissing('messages', [
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'reply_to_id' => $parentMessage->id,
        ]);
    }
}
