<?php

namespace Database\Factories;

use App\Enums\Tagtype;
use App\Models\Product;
use App\Models\Report;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),  // 使用假數據生成器來生成商品名稱
            'price' => $this->faker->randomFloat(0, 10, 1000),  // 隨機生成價格，範圍10到1000
            'description' => $this->faker->text(50),  // 使用 text(50) 方法來限制 description 欄位為最多 50 個字
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function hasTags()
    {
        return $this->afterCreating(function (Product $product) {
            // 動態獲取每種枚舉類型的標籤
            $tagIds = [];
            foreach (Tagtype::cases() as $tagType) {
                $tag = Tag::where('type', $tagType->value)->inRandomOrder()->first();
                if ($tag) {
                    $tagIds[] = $tag->id;
                }
            }

            // 同步標籤到產品
            $product->tags()->sync($tagIds);
        });
    }

    public function hasReports(int $count = 0)
    {
        return $this->afterCreating(function (Product $product) use ($count) {
            if ($count > 0) {
                $reports = Report::factory()->count($count)->forProduct()->create();
                $product->reports()->sync($reports->pluck('id'));
            }
        });
    }
}
