<?php

namespace Database\Factories;

use App\Enums\ReportType as ReportTypeEnum;
use App\Models\Report;
use App\Models\ReportType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition()
    {
        return [
            'report_type_id' => ReportType::factory(),
            'user_id' => User::factory(),
            'description' => $this->faker->text(200),
        ];
    }

    public function forProduct()
    {
        return $this->state(function () {
            return [
                'report_type_id' => ReportType::where('type', ReportTypeEnum::Product->value)->inRandomOrder()->first()->id,
            ];
        });
    }

    public function forMessage()
    {
        return $this->state(function () {
            return [
                'report_type_id' => ReportType::where('type', ReportTypeEnum::Message->value)->inRandomOrder()->first()->id,
            ];
        });
    }
}
