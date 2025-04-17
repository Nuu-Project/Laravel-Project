<?php

namespace Tests\Feature\Guest;

use App\Enums\ProductStatus;
use App\Models\Message;
use App\Models\Product;
use App\Models\ReportType;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createProductWithStatus(ProductStatus $status): Product
    {
        return Product::factory()->create([
            'status' => $status,
            'user_id' => User::factory()->create()->id,
        ]);
    }

    private function createProductWithTag(array $tagData = []): Product
    {
        $product = $this->createProductWithStatus(ProductStatus::Active);
        $tag = Tag::factory()->create(array_merge([
            'type' => 'product',
        ], $tagData));

        $product->tags()->attach($tag);

        return $product;
    }

    public function test_can_view_active_products()
    {
        $active = $this->createProductWithStatus(ProductStatus::Active);
        $inactive = $this->createProductWithStatus(ProductStatus::Inactive);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertViewIs('guest.products.index')
            ->assertSee($active->name)
            ->assertDontSee($inactive->name);
    }

    public function test_can_filter_products_by_tags()
    {
        $product1 = $this->createProductWithTag(['name' => ['zh_TW' => '標籤1']]);
        $product2 = $this->createProductWithTag(['name' => ['zh_TW' => '標籤2']]);

        $this->get(route('products.index', ['filter' => ['tags' => [$product1->tags->first()->id]]]))
            ->assertOk()
            ->assertSee($product1->name)
            ->assertDontSee($product2->name);
    }

    public function test_can_view_product_details()
    {
        $product = $this->createProductWithStatus(ProductStatus::Active);
        $message = Message::factory()->create(['product_id' => $product->id]);

        $reports = ReportType::factory()->sequence(
            ['type' => '商品', 'order_column' => 1],
            ['type' => '留言', 'order_column' => 2]
        )->count(2)->create();

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertViewIs('guest.products.show')
            ->assertSee($product->name)
            ->assertSee($message->content)
            ->assertViewHasAll(['productReports', 'messageReports']);
    }

    public function test_cannot_view_inactive_product()
    {
        $product = $this->createProductWithStatus(ProductStatus::Inactive);
        $this->get(route('products.show', $product))->assertNotFound();
    }
}
