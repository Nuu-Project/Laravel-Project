<?php

namespace Tests\Feature\Web\User;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductMessageControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    #[Test]
    public function authenticated_user_can_store_a_message_for_a_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $messageText = $this->faker->sentence;

        $response = $this->actingAs($user)
            ->post(route('user.products.messages.store', $product), [
                'message' => $messageText,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $this->assertDatabaseHas('messages', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'message' => $messageText,
        ]);
    }

    #[Test]
    public function guest_cannot_store_a_message_for_a_product()
    {
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

    #[Test]
    public function message_field_is_required_when_storing_a_message()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('user.products.messages.store', $product), [
                'message' => '',
            ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseMissing('messages', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    #[Test]
    public function authenticated_user_can_view_the_edit_form_for_their_own_message()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);

        $response = $this->actingAs($user)
            ->get(route('user.products.messages.edit', [$product, $message]));

        $response->assertOk();
        $response->assertViewIs('messages.edit');
        $response->assertViewHas('message', $message);
        $response->assertViewHas('productId', $product);
    }

    #[Test]
    public function authenticated_user_cannot_view_the_edit_form_for_another_users_message()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => $otherUser->id, 'product_id' => $product->id]);

        $response = $this->actingAs($user)
            ->get(route('user.products.messages.edit', [$product, $message]));

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_view_the_edit_form_for_a_message()
    {
        $product = Product::factory()->create();
        $message = Message::factory()->create(['product_id' => $product->id]);

        $response = $this->get(route('user.products.messages.edit', [$product, $message]));

        $response->assertRedirect('/login');
    }

    #[Test]
    public function authenticated_user_can_update_their_own_message()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'message' => 'Original message']);
        $updatedMessageText = $this->faker->sentence;

        $response = $this->actingAs($user)
            ->put(route('user.products.messages.update', [$product, $message]), [
                'message' => $updatedMessageText,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'message' => $updatedMessageText,
        ]);
    }

    #[Test]
    public function authenticated_user_cannot_update_another_users_message()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => $otherUser->id, 'product_id' => $product->id, 'message' => 'Original message']);
        $updatedMessageText = $this->faker->sentence;

        $response = $this->actingAs($user)
            ->put(route('user.products.messages.update', [$product, $message]), [
                'message' => $updatedMessageText,
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'message' => 'Original message',
        ]);
    }

    #[Test]
    public function guest_cannot_update_a_message()
    {
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

    #[Test]
    public function message_field_is_required_when_updating_a_message()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => $user->id, 'product_id' => $product->id, 'message' => 'Original message']);

        $response = $this->actingAs($user)
            ->put(route('user.products.messages.update', [$product, $message]), [
                'message' => '',
            ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'message' => 'Original message',
        ]);
    }

    #[Test]
    public function authenticated_user_can_delete_their_own_message()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);

        $response = $this->actingAs($user)
            ->delete(route('user.products.messages.destroy', [$product, $message]));

        $response->assertRedirect(route('products.show', $product));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    #[Test]
    public function authenticated_user_cannot_delete_another_users_message()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create();
        $message = Message::factory()->create(['user_id' => $otherUser->id, 'product_id' => $product->id]);

        $response = $this->actingAs($user)
            ->delete(route('user.products.messages.destroy', [$product, $message]));

        $response->assertStatus(403);
        $this->assertDatabaseHas('messages', ['id' => $message->id]);
    }

    #[Test]
    public function guest_cannot_delete_a_message()
    {
        $product = Product::factory()->create();
        $message = Message::factory()->create(['product_id' => $product->id]);

        $response = $this->delete(route('user.products.messages.destroy', [$product, $message]));

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('messages', ['id' => $message->id]);
    }

    #[Test]
    public function authenticated_user_can_reply_to_a_message()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $parentMessage = Message::factory()->create(['product_id' => $product->id]);
        $replyText = $this->faker->sentence;

        $response = $this->actingAs($user)
            ->post(route('user.products.messages.reply', [$product, $parentMessage]), [
                'message' => $replyText,
            ]);

        $response->assertRedirect(route('products.show', $product));
        $this->assertDatabaseHas('messages', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'message' => $replyText,
            'reply_to_id' => $parentMessage->id,
        ]);
    }

    #[Test]
    public function guest_cannot_reply_to_a_message()
    {
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

    #[Test]
    public function message_field_is_required_when_replying_to_a_message()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $parentMessage = Message::factory()->create(['product_id' => $product->id]);

        $response = $this->actingAs($user)
            ->post(route('user.products.messages.reply', [$product, $parentMessage]), [
                'message' => '',
            ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseMissing('messages', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'reply_to_id' => $parentMessage->id,
        ]);
    }
}
