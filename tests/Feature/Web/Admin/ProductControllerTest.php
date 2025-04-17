<?php

namespace Tests\Feature\Web\Admin;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin();
    }

    public function test_admin_can_view_products_list()
    {
        $admin = User::factory()->create()->assignRole('admin');
        $product = Product::factory()->create(['status' => ProductStatus::Active]);

        $this->actingAs($admin)
            ->get(route('admin.products.index'))
            ->assertStatus(200)
            ->assertViewIs('admin.products.index')
            ->assertSee($product->name);
    }

    public function test_admin_can_filter_products_by_name()
    {
        $admin = User::factory()->create()->assignRole('admin');
        $productA = Product::factory()->create(['name' => 'Test Product A']);
        $productB = Product::factory()->create(['name' => 'Test Product B']);

        $this->actingAs($admin)
            ->get(route('admin.products.index', ['filter[name]' => 'Product A']))
            ->assertSee($productA->name)
            ->assertDontSee($productB->name);
    }

    public function test_admin_can_filter_products_by_user_name()
    {
        $admin = User::factory()->create(['name' => 'Admin'])->assignRole('admin');
        $user = User::factory()->create(['name' => 'John Doe']);
        $product = Product::factory()->create(['user_id' => $user->id]);

        $this->actingAs($admin)
            ->get(route('admin.products.index', ['filter[user]' => 'John']))
            ->assertSee($product->name);
    }

    public function test_admin_can_toggle_product_status()
    {
        $admin = User::factory()->create()->assignRole('admin');
        $product = Product::factory()->create(['status' => ProductStatus::Active]);

        $this->actingAs($admin)
            ->patch(route('admin.products.inactive', $product))
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success');

        $this->assertEquals(ProductStatus::Inactive, $product->fresh()->status);
    }

    public function test_guest_cannot_access_products_page()
    {
        $this->logout();

        $this->get(route('admin.products.index'))
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_user_cannot_access_products_page()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('admin.products.index'))
            ->assertForbidden();
    }

    public function test_products_are_paginated()
    {
        $admin = User::factory()->create()->assignRole('admin');
        Product::factory()->count(25)->create();

        $this->actingAs($admin)
            ->get(route('admin.products.index'))
            ->assertViewHas('products', function ($paginator) {
                return $paginator->count() === 10; // 假設每頁 10 筆
            });
    }

    public function test_invalid_filter_parameter_does_not_break_page()
    {
        $admin = User::factory()->create()->assignRole('admin');

        $this->actingAs($admin)
            ->get(route('admin.products.index', ['filter[invalid]' => 'test']))
            ->assertStatus(400);
    }
}
