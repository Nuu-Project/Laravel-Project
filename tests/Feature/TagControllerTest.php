<?php

namespace Tests\Feature;

use App\Enums\ProductStatus;
use App\Models\Message;
use App\Models\Product;
use App\Models\ReportType;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase; // 使用資料庫重置 trait

    #[Test]
    protected function setUp(): void
    {
        parent::setUp();

        // 建立管理員角色和標籤管理權限
        $adminRole = Role::create(['name' => 'admin']);
        Permission::create(['name' => 'manage tags']);
        $adminRole->givePermissionTo('manage tags');
    }

    #[Test]
    public function test_admin_can_view_tags_list()
    {
        // 建立具有管理員權限的使用者
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // 建立測試用標籤
        Tag::create([
            'name' => ['zh_TW' => '測試標籤'],
            'slug' => ['zh_TW' => 'test'],
            'type' => 'test',
            'order_column' => 1,
        ]);

        // 模擬管理員登入
        $this->actingAs($admin);

        // 發送請求查看標籤列表
        $response = $this->get(route('admin.tags.index'));

        // 驗證回應
        $response->assertStatus(200)
            ->assertViewIs('admin.tags.index')
            ->assertSee('測試標籤');
    }

    #[Test]
    public function test_admin_can_create_tag()
    {
        // 建立具有管理員權限的使用者
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // 模擬管理員登入
        $this->actingAs($admin);

        // 發送請求建立新標籤
        $response = $this->post(route('admin.tags.store'), [
            'name' => '新標籤',
            'slug' => 'newtag',
            'type' => 'test',
            'order_column' => 1,
        ]);

        // 驗證是否重導向到標籤列表
        $response->assertRedirect(route('admin.tags.index'));
        // 驗證資料庫中是否存在新建立的標籤
        $this->assertDatabaseHas('tags', [
            'name->zh_TW' => '新標籤',
            'slug->zh_TW' => 'newtag',
        ]);
    }

    #[Test]
    public function test_admin_can_update_tag()
    {
        // 建立具有管理員權限的使用者
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // 建立要更新的標籤
        $tag = Tag::create([
            'name' => ['zh_TW' => '舊標籤'],
            'slug' => ['zh_TW' => 'oldtag'],
            'type' => 'test',
            'order_column' => 1,
        ]);

        // 模擬管理員登入
        $this->actingAs($admin);

        // 發送請求更新標籤
        $response = $this->patch(route('admin.tags.update', $tag), [
            'name' => '更新標籤',
            'slug' => 'updatedtag',
            'type' => 'test',
            'order_column' => 2,
        ]);

        // 驗證是否重導向到標籤列表
        $response->assertRedirect(route('admin.tags.index'));
        // 驗證資料庫中標籤是否已更新
        $this->assertDatabaseHas('tags', [
            'name->zh_TW' => '更新標籤',
            'slug->zh_TW' => 'updatedtag',
        ]);
    }

    #[Test]
    public function test_admin_can_delete_tag()
    {
        // 建立具有管理員權限的使用者
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // 建立要刪除的標籤
        $tag = Tag::create([
            'name' => ['zh_TW' => '要刪除的標籤'],
            'slug' => ['zh_TW' => 'deletetag'],
            'type' => 'test',
            'order_column' => 1,
        ]);

        // 模擬管理員登入
        $this->actingAs($admin);

        // 發送請求刪除標籤
        $response = $this->delete(route('admin.tags.destroy', $tag));

        // 驗證是否重導向到標籤列表
        $response->assertRedirect(route('admin.tags.index'));
        // 驗證標籤是否已被軟刪除
        $this->assertSoftDeleted($tag);
    }

    #[Test]
    public function test_admin_can_restore_tag()
    {
        // 建立具有管理員權限的使用者
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // 建立並軟刪除標籤
        $tag = Tag::create([
            'name' => ['zh_TW' => '要恢復的標籤'],
            'slug' => ['zh_TW' => 'restoretag'],
            'type' => 'test',
            'order_column' => 1,
        ]);
        $tag->delete();

        // 模擬管理員登入
        $this->actingAs($admin);

        // 發送請求恢復標籤
        $response = $this->post(route('admin.tags.restore', $tag));

        // 驗證是否重導向到標籤列表
        $response->assertRedirect(route('admin.tags.index'));
        // 驗證標籤是否已被恢復
        $this->assertNotSoftDeleted($tag);
    }

    #[Test]
    public function test_can_view_product_details()
    {
        // 建立測試用戶和商品
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'status' => ProductStatus::Active,
            'user_id' => $user->id,
        ]);

        // 建立測試留言
        $message = Message::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        // 建立商品檢舉類型
        $reportType1 = ReportType::create([
            'name' => ['zh_TW' => '商品問題'],
            'type' => '商品',
            'order_column' => 1,
        ]);

        // 建立留言檢舉類型
        $reportType2 = ReportType::create([
            'name' => ['zh_TW' => '留言問題'],
            'type' => '留言',
            'order_column' => 2,
        ]);

        // 發送請求查看商品詳情
        $response = $this->get(route('products.show', $product));

        // 驗證回應
        $response->assertStatus(200)
            ->assertViewIs('guest.products.show')
            ->assertSee($product->name)
            ->assertSee($message->content)
            // 驗證商品檢舉類型資料
            ->assertViewHas('productReports', function ($reports) use ($reportType1) {
                return $reports->has($reportType1->id)
                    && $reports[$reportType1->id] === $reportType1->name;
            })
            // 驗證留言檢舉類型資料
            ->assertViewHas('messageReports', function ($reports) use ($reportType2) {
                return $reports->has($reportType2->id)
                    && $reports[$reportType2->id] === $reportType2->name;
            });
    }
}
