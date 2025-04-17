<?php

namespace Tests\Feature\Api\User;

use App\Enums\ReportType as ReportTypeEnum;
use App\Models\Product;
use App\Models\Report;
use App\Models\ReportType;
use Tests\TestCase;

class ProductReportControllerTest extends TestCase
{
    private Product $product;

    private ReportType $reportType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();
        $this->product = Product::factory()->create();
        $this->reportType = ReportType::factory()->create([
            'type' => ReportTypeEnum::Product->value,
        ]);
    }

    public function test_it_can_store_a_product_report_successfully(): void
    {
        $this->reportsProduct([
            'report_type_id' => $this->reportType->id,
            'description' => '這個產品有問題。',
        ])->assertOk()
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('reports', [
            'report_type_id' => $this->reportType->id,
            'user_id' => auth()->id(),
            'description' => '這個產品有問題。',
        ]);

        $this->assertDatabaseHas('reportables', [
            'report_id' => Report::latest()->first()->id,
            'reportable_id' => $this->product->id,
            'reportable_type' => Product::class,
        ]);
    }

    public function test_it_validates_report_type_id_is_required(): void
    {
        $this->reportsProduct([
            'description' => '測試描述',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['report_type_id']);
    }

    public function test_it_validates_report_type_id_exists(): void
    {
        $this->reportsProduct([
            'report_type_id' => 999,
            'description' => '測試描述',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['report_type_id']);
    }

    public function test_it_validates_report_type_id_is_for_product(): void
    {
        $this->reportType = ReportType::factory()->create(['type' => ReportTypeEnum::Message->value]);
        $this->reportsProduct([
            'report_type_id' => $this->reportType->id,
            'description' => '測試描述',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['report_type_id']);
    }

    public function test_it_validates_description_is_required(): void
    {
        $this->reportsProduct([
            'report_type_id' => $this->reportType->id,
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['description']);
    }

    public function test_it_validates_description_max_length(): void
    {
        $longDescription = str_repeat('a', 256);

        $this->reportsProduct([
            'report_type_id' => $this->reportType->id,
            'description' => $longDescription,
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['description']);
    }

    public function test_it_prevents_duplicate_reports(): void
    {
        $this->product->reports()->create([
            'report_type_id' => $this->reportType->id,
            'user_id' => auth()->id(),
            'description' => '第一次檢舉。',
        ]);

        $this->reportsProduct([
            'report_type_id' => $this->reportType->id,
            'description' => '第二次檢舉。',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['report_type_id']);

        $this->assertCount(1, Report::where('user_id', auth()->id())->where('report_type_id', $this->reportType->id)->get());
    }

    private function reportsProduct(array $data): \Illuminate\Testing\TestResponse
    {
        return $this->postJson(route('api.products.reports.store', $this->product), $data);
    }
}
