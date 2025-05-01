<?php

namespace Tests\Feature\Web\Guest;

use App\Enums\ProductStatus;
use App\Enums\Tagtype;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    private Tag $subject1;

    private Tag $subject2;

    private Tag $subject3;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        Tag::query()->delete();
        $this->subject1 = $this->createTagSubject();
        $this->subject2 = $this->createTagSubject();
        $this->subject3 = $this->createTagSubject();
    }

    public function test_homepage_returns_three_active_products(): void
    {

        $product1 = $this->createProduct([
            'created_at' => Carbon::now()->subDays(1),
        ]);
        $product1->tags()->sync([$this->subject1->id]);

        $product2 = $this->createProduct([
            'created_at' => Carbon::now()->subDays(2),
        ]);
        $product2->tags()->sync([$this->subject2->id]);

        $product3 = $this->createProduct([
            'created_at' => Carbon::now()->subDays(3),
        ]);
        $product3->tags()->sync([$this->subject3->id]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('Home')
            ->assertViewHas('products', function ($products) {
                return $products->count() === 3 &&
                    $products->every(fn ($p) => $p->status->value === ProductStatus::Active->value);
            });
    }

    public function test_homepage_includes_additional_products_if_top_tag_products_are_less_than_three(): void
    {
        $product1 = $this->createProduct([
            'created_at' => Carbon::now()->subDays(1),
        ]);
        $product1->tags()->sync([$this->subject1->id]);

        $additionalProduct1 = $this->createProduct([
            'created_at' => Carbon::now(),
        ]);

        $additionalProduct2 = $this->createProduct([
            'created_at' => Carbon::now()->subDays(10),
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('products', function ($products) use ($product1, $additionalProduct1, $additionalProduct2) {
                return $products->count() === 3 &&
                       $products->contains(fn ($p) => $p->id === $product1->id) &&
                       $products->contains(fn ($p) => $p->id === $additionalProduct1->id) &&
                       $products->contains(fn ($p) => $p->id === $additionalProduct2->id);
            });
    }

    private function createTagSubject(array $state = []): Tag
    {
        return Tag::factory()
            ->state($state + [
                'type' => Tagtype::Subject->value,
            ])
            ->create();
    }

    private function createProduct(array $state = []): Product
    {
        return Product::factory()
            ->state($state + [
                'status' => ProductStatus::Active->value,
                'created_at' => Carbon::now()->subDays(1),
            ])
            ->create();
    }
}
