<?php

namespace Database\Factories;

use App\Enums\Tagtype;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition()
    {
        return [
            'name' => [
                'en' => $this->faker->word(),
                'zh' => $this->faker->word(),
            ],
            'slug' => [
                'en' => $this->faker->slug(),
                'zh' => $this->faker->slug(),
            ],
            'type' => Tagtype::Subject->value,
            'order_column' => $this->faker->numberBetween(900, 950),
        ];
    }
}
