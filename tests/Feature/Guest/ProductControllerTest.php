<?php

namespace Tests\Feature\Guest;

use App\Enums\ProductStatus;
use App\Models\Message;
use App\Models\Product;
use App\Models\ReportType;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    // 測試是否可以查看所有「啟用」狀態的商品

    #[Test]
    public function test_can_view_active_products()
    {
        $user = User::factory()->create();

        // 建立啟用商品
        Product::factory()->create([
            'name' => 'Active Product',
            'status' => ProductStatus::Active,
            'user_id' => $user->id,
        ]);

        // 建立未啟用商品
        Product::factory()->create([
            'name' => 'Inactive Product',
            'status' => ProductStatus::Inactive,
            'user_id' => $user->id,
        ]);

        // 發送請求至商品列表頁面
        $response = $this->get(route('products.index'));

        $response->assertStatus(200)
            ->assertViewIs('guest.products.index') // 確保載入正確的視圖
            ->assertSeeText('Active Product') // 確保啟用商品出現在頁面上
            ->assertDontSeeText('Inactive Product'); // 確保未啟用商品不會顯示
    }

    // 測試是否可以透過標籤篩選商品
    #[Test]
    public function test_can_filter_products_by_tags()
    {
        $user = User::factory()->create();

        // 建立兩個標籤
        $tag1 = Tag::create([
            'name' => ['zh_TW' => '標籤1'],
            'slug' => ['zh_TW' => 'tag1'],
            'type' => 'product',
            'order_column' => 1,
        ]);

        $tag2 = Tag::create([
            'name' => ['zh_TW' => '標籤2'],
            'slug' => ['zh_TW' => 'tag2'],
            'type' => 'product',
            'order_column' => 2,
        ]);

        // 建立兩個商品並分別附加標籤
        $product1 = Product::factory()->create([
            'name' => 'Product One',
            'status' => ProductStatus::Active,
            'user_id' => $user->id,
        ]);
        $product1->tags()->attach($tag1);

        $product2 = Product::factory()->create([
            'name' => 'Product Two',
            'status' => ProductStatus::Active,
            'user_id' => $user->id,
        ]);
        $product2->tags()->attach($tag2);

        // 依標籤篩選商品
        $response = $this->get(route('products.index', [
            'filter' => ['tags' => [$tag1->id]],
        ]));

        $response->assertStatus(200)
            ->assertSeeText('Product One') // 確保篩選結果包含 Product One
            ->assertDontSeeText('Product Two'); // 確保未符合條件的商品未顯示
    }

    // 測試是否可以查看商品詳細資訊
    #[Test]
    public function test_can_view_product_details()
    {
        $user = User::factory()->create();

        // 建立商品
        $product = Product::factory()->create([
            'status' => ProductStatus::Active,
            'user_id' => $user->id,
        ]);

        // 建立與該商品相關的留言
        $message = Message::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        // 建立檢舉類型
        $productReport = ReportType::create([
            'name' => ['zh_TW' => '商品問題'],
            'type' => '商品',
            'order_column' => 1,
        ]);

        $messageReport = ReportType::create([
            'name' => ['zh_TW' => '留言問題'],
            'type' => '留言',
            'order_column' => 2,
        ]);

        // 查看商品詳細頁面
        $response = $this->get(route('products.show', $product));

        $response->assertStatus(200)
            ->assertViewIs('guest.products.show') // 確保載入正確的視圖
            ->assertSee($product->name) // 確保商品名稱顯示
            ->assertSee($message->content) // 確保留言內容顯示
            ->assertViewHas('productReports', function ($reports) use ($productReport) {
                return $reports->has($productReport->id)
                    && $reports[$productReport->id] === $productReport->name;
            })
            ->assertViewHas('messageReports', function ($reports) use ($messageReport) {
                return $reports->has($messageReport->id)
                    && $reports[$messageReport->id] === $messageReport->name;
            });
    }

    // 測試無法查看未啟用的商品
    #[Test]
    public function test_cannot_view_inactive_product()
    {
        $user = User::factory()->create();

        // 建立未啟用商品
        $product = Product::factory()->create([
            'status' => ProductStatus::Inactive,
            'user_id' => $user->id,
        ]);

        // 嘗試查看未啟用商品，應回傳 404 錯誤
        $response = $this->get(route('products.show', $product));

        $response->assertStatus(404);
    }
}
