<?php

namespace Database\Factories;

use App\Enums\ReportType as ReportTypeEnum;
use App\Models\ReportType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportTypeFactory extends Factory
{
    protected $model = ReportType::class;

    public function definition()
    {
        return [
            'name' => [
                'en' => $this->faker->word(),
                'zh_TW' => $this->faker->word(),
            ],
            'type' => $this->faker->randomElement([
                ReportTypeEnum::Product->value,
                ReportTypeEnum::Message->value,
            ]),
            'order_column' => $this->faker->numberBetween(900, 950), // 假定的排序欄位
        ];
    }
}
