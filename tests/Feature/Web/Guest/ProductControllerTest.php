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

    public function test_can_view_active_products(): void
    {
        $active = $this->createProductWithStatus(ProductStatus::Active);
        $inactive = $this->createProductWithStatus(ProductStatus::Inactive);

        $response = $this->get(route('products.index'))
            ->assertOk()
            ->assertViewIs('guest.products.index')
            ->assertSeeText($active->name)
            ->assertDontSeeText($inactive->name, false);

        $products = $response->viewData('products');

        $this->assertTrue($products->contains($active));
        $this->assertFalse($products->contains($inactive));
    }

    public function test_can_filter_products_by_tags()
    {
        $tag1 = $this->createTag([
            'name' => ['zh_TW' => '標籤1'],
        ]);

        $tag2 = $this->createTag([
            'name' => ['zh_TW' => '標籤2'],
        ]);

        $product1 = $this->createProductWithStatus(ProductStatus::Active);
        $product2 = $this->createProductWithStatus(ProductStatus::Active);

        $product1->tags()->attach($tag1);
        $product2->tags()->attach($tag2);

        $response = $this->get(route('products.index', [
            'filter' => ['tags' => [$tag1->id]],
        ]))->assertOk();

        $products = $response->viewData('products');
        $this->assertTrue($products->contains($product1));
        $this->assertFalse($products->contains($product2));

        $response->assertSeeText($product1->name)
            ->assertDontSeeText($product2->name);
    }

    public function test_can_view_product_details()
    {
        $product = $this->createProductWithStatus(ProductStatus::Active);
        $message = Message::factory()->create(['product_id' => $product->id]);

        $reports = ReportType::factory()
            ->sequence([
                'type' => '商品', 'order_column' => 1,
            ], [
                'type' => '留言', 'order_column' => 2,
            ])->count(2)->create();

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

    private function createProductWithTag(array $tagData = []): Product
    {
        $product = $this->createProductWithStatus(ProductStatus::Active);
        $tag = $this->createTag($tagData);
        $product->tags()->attach($tag);

        return $product;
    }

    private function createProductWithStatus(ProductStatus $status): Product
    {
        return Product::factory()->create([
            'status' => $status,
            'user_id' => User::factory()->create()->id,
        ]);
    }

    private function createTag(array $state = []): Tag
    {
        return Tag::factory()->state($state + [
            'type' => 'product',
        ])->create();
    }
}
