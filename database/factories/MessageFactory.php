<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'message' => $this->faker->sentence,
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function withReports(int $count = 0)
    {
        return $this->afterCreating(function (Message $message) use ($count) {
            if ($count > 0) {
                $reports = Report::factory()->count($count)->forMessage()->create();
                $message->reports()->sync($reports->pluck('id'));
            }
        });
    }
}
