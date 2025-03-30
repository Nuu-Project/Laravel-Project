<?php

namespace Tests\Feature;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 設定測試環境，建立角色和權限
     */
    #[Test]
    protected function setUp(): void
    {
        parent::setUp();

        // 建立管理員角色
        $adminRole = Role::create(['name' => 'admin']);

        // 建立權限
        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'manage products']);

        // 賦予管理員角色相關權限
        $adminRole->givePermissionTo(['view products', 'manage products']);
    }

    /**
     * 測試管理員是否可以查看商品列表
     */
    #[Test]
    public function test_can_view_products_list()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // 建立一個啟用狀態的商品
        $product = Product::factory()->create([
            'user_id' => $admin->id,
            'status' => ProductStatus::Active,
        ]);

        // 以管理員身份訪問商品列表頁面
        $this->actingAs($admin);
        $response = $this->get(route('admin.products.index'));

        // 確保回應狀態碼為 200，並載入正確的視圖，且商品名稱可見
        $response->assertStatus(200)
            ->assertViewIs('admin.products.index')
            ->assertSee($product->name);
    }

    /**
     * 測試是否可以透過名稱篩選商品
     */
    #[Test]
    public function test_can_filter_products_by_name()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // 建立兩個商品
        $productA = Product::factory()->create([
            'name' => 'Test Product A',
            'user_id' => $admin->id,
        ]);

        $productB = Product::factory()->create([
            'name' => 'Test Product B',
            'user_id' => $admin->id,
        ]);

        // 以管理員身份進行篩選請求
        $this->actingAs($admin);
        $response = $this->get(route('admin.products.index', [
            'filter' => ['name' => 'Product A'],
        ]));

        // 確保回應狀態碼為 200，並且僅顯示符合條件的商品
        $response->assertStatus(200)
            ->assertSee($productA->name)
            ->assertDontSee($productB->name);
    }

    /**
     * 測試是否可以切換商品的啟用狀態
     */
    #[Test]
    public function test_can_toggle_product_status()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // 建立一個預設為啟用狀態的商品
        $product = Product::factory()->create([
            'user_id' => $admin->id,
            'status' => ProductStatus::Active,
        ]);

        // 以管理員身份訪問
        $this->actingAs($admin);

        // 發送請求以切換商品狀態
        $response = $this->put(route('admin.products.inactive', $product));

        // 確保回應會導向商品列表頁面，並檢查商品狀態是否成功變更
        $response->assertRedirect(route('admin.products.index'));
        $this->assertEquals(ProductStatus::Inactive, $product->fresh()->status);
    }
}
