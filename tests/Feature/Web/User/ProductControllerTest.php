<?php

namespace Tests\Feature\Web\User;

use App\Enums\ProductStatus;
use App\Enums\Tagtype;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsUser();
        $this->createBasicTags();
    }

    public function test_user_can_view_their_products()
    {
        Product::factory()
            ->count(3)
            ->create(['user_id' => auth()->id()]);

        $this->get(route('user.products.index'))
            ->assertOk()
            ->assertViewIs('user.products.index')
            ->assertViewHas('userProducts');
    }

    public function test_user_can_create_product()
    {
        Storage::fake('public_images');
        Storage::fake('temp');

        // 建立測試圖片
        $image = UploadedFile::fake()->image('test.jpg');
        $tempPath = 'temp/product.jpg';
        Storage::disk('temp')->put($tempPath, file_get_contents($image));

        // 創建必要的標籤
        $tags = [
            'grade' => Tag::factory()->create(['type' => TagType::Grade->value]),
            'semester' => Tag::factory()->create(['type' => TagType::Semester->value]),
            'subject' => Tag::factory()->create(['type' => TagType::Subject->value]),
            'category' => Tag::factory()->create(['type' => TagType::Category->value]),
        ];

        $data = [
            'name' => $this->faker->text(20),
            'price' => $this->faker->numberBetween(1, 1000),
            'description' => $this->faker->text(50),
            'grade' => $tags['grade']->id,
            'semester' => $tags['semester']->id,
            'subject' => $tags['subject']->id,
            'category' => $tags['category']->id,
            'encrypted_image_path' => [encrypt($tempPath)],
        ];

        $this->post(route('user.products.store'), $data)
            ->assertRedirect()
            ->assertSessionHas('success', '商品已成功創建！');
    }

    public function test_user_crud_permissions()
    {
        $otherProduct = Product::factory()->create();

        // 測試不能編輯他人商品
        $this->get(route('user.products.edit', $otherProduct))
            ->assertForbidden();

        // 測試不能刪除他人商品
        $this->delete(route('user.products.destroy', $otherProduct))
            ->assertForbidden();

        // 測試不能更改他人商品狀態
        $this->patch(route('user.products.inactive', $otherProduct))
            ->assertForbidden();
    }

    public function test_validation_rules()
    {
        // 測試新增驗證
        $this->post(route('user.products.store'), [])
            ->assertSessionHasErrors(['name', 'price', 'description', 'grade', 'semester', 'subject', 'category']);

        // 測試更新驗證
        $product = Product::factory()->create(['user_id' => auth()->id()]);
        $this->put(route('user.products.update', $product), [])
            ->assertSessionHasErrors(['name', 'description', 'grade', 'semester', 'subject', 'category']);
    }

    public function test_guest_cannot_access_product_features()
    {
        $this->logout();
        $product = Product::factory()->create();

        $routes = [
            'get' => ['user.products.index', 'user.products.create', 'user.products.edit'],
            'post' => ['user.products.store'],
            'put' => ['user.products.update'],
            'patch' => ['user.products.inactive'],
            'delete' => ['user.products.destroy'],
        ];

        foreach ($routes as $method => $routeNames) {
            foreach ($routeNames as $routeName) {
                $this->$method(route($routeName, $product))
                    ->assertRedirect('/login');
            }
        }
    }

    public function test_user_can_toggle_product_status()
    {
        $product = Product::factory()->create([
            'user_id' => auth()->id(),
            'status' => ProductStatus::Active,
        ]);

        $this->patch(route('user.products.inactive', $product))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => ProductStatus::Inactive->value,
        ]);
    }

    public function test_cache_is_cleared_when_product_status_inactive()
    {
        $product = Product::factory()->create([
            'user_id' => auth()->id(),
            'status' => ProductStatus::Active,
        ]);

        Cache::put('top_tags_products', collect([
            ['id' => $product->id, 'name' => $product->name],
        ]));

        $this->assertTrue(Cache::has('top_tags_products'));
        $cachedProducts = Cache::get('top_tags_products');
        $this->assertEquals($product->id, $cachedProducts->first()['id']);

        $this->patch(route('user.products.inactive', $product))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => ProductStatus::Inactive->value,
        ]);

        $this->assertFalse(Cache::has('top_tags_products'));
    }
}
