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
        $product = $this->createProduct([
            'status' => ProductStatus::Active,
        ]);

        $this->get(route('admin.products.index'))
            ->assertOk()
            ->assertViewIs('admin.products.index')
            ->assertSee($product->name);
    }

    public function test_admin_can_filter_products_by_name()
    {
        $productA = $this->createProduct([
            'name' => 'Test Product A',
        ]);

        $productB = $this->createProduct([
            'name' => 'Test Product B',
        ]);

        $this->get(route('admin.products.index', [
            'filter[name]' => 'Product A',
        ]))->assertSee($productA->name)
            ->assertDontSee($productB->name);
    }

    public function test_admin_can_filter_products_by_user_name()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
        ]);

        $product = $this->createProduct([
            'user_id' => $user->id,
        ]);

        $this->get(route('admin.products.index', [
            'filter[user]' => 'John',
        ]))->assertSee($product->name);
    }

    public function test_admin_can_toggle_product_status()
    {
        $product = $this->createProduct([
            'status' => ProductStatus::Active,
        ]);

        $this->patch(route('admin.products.inactive', $product))
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
        $user = $this->createUser();
        $this->actingAs($user)
            ->get(route('admin.products.index'))
            ->assertForbidden();
    }

    public function test_products_are_paginated()
    {
        Product::factory()->count(25)->create();

        $this->get(route('admin.products.index'))
            ->assertViewHas('products', function ($paginator) {
                return $paginator->count() === 10;
            });
    }

    public function test_invalid_filter_parameter_does_not_break_page()
    {
        $this->get(route('admin.products.index', [
            'filter[invalid]' => 'test',
        ]))->assertStatus(400);
    }

    public function createProduct(array $state = []): Product
    {
        return Product::factory()->state($state)->create();
    }
}
