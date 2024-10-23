<?php

namespace Database\Factories;

use App\Models\Product;
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
            'description' => $this->faker->paragraph(),  // 隨機生成一段文字作為商品描述
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
