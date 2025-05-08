<?php

namespace Tests\Feature\Web\Admin;

use App\Enums\ReportType as ReportTypeEnum;
use App\Models\Message;
use App\Models\Product;
use App\Models\ReportType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createReportType();
        $this->createReportType([
            'type' => ReportTypeEnum::Message->value(),
        ]);

        $this->actingAsAdmin();
    }

    public function test_index_displays_filtered_reportables_by_type(): void
    {
        $product = $this->createProduct();
        $message = $this->createMessage();

        $response = $this->get(route('admin.reports.index', ['filter[type]' => ReportTypeEnum::Product->value()]));

        $response->assertOk();
        $response->assertViewIs('admin.reports.index');
        $response->assertViewHas('reportables', function ($reportables) use ($product) {
            return $reportables->contains(function ($reportable) use ($product) {
                return $reportable->reportable_type === Product::class && $reportable->reportable_id === $product->id;
            });
        });

        $response = $this->get(route('admin.reports.index', ['filter[type]' => ReportTypeEnum::Message->value()]));

        $response->assertOk();
        $response->assertViewIs('admin.reports.index');
        $response->assertViewHas('reportables', function ($reportables) use ($message) {
            return $reportables->contains(function ($reportable) use ($message) {
                return $reportable->reportable_type === Message::class && $reportable->reportable_id === $message->id;
            });
        });
    }

    public function test_index_displays_filtered_reportables_by_name(): void
    {
        $product = $this->createProduct();
        $message = $this->createMessage();

        $response = $this->get(route('admin.reports.index', [
            'filter[type]' => ReportTypeEnum::Product->value(),
            'filter[name]' => $product->name,
        ]));

        $response->assertOk();
        $response->assertViewIs('admin.reports.index');
        $response->assertViewHas('reportables', function ($reportables) use ($product) {
            return $reportables->contains(function ($reportable) use ($product) {
                return $reportable->reportable_type === Product::class && $reportable->reportable_id === $product->id;
            });
        });

        $response = $this->get(route('admin.reports.index', [
            'filter[type]' => ReportTypeEnum::Message->value(),
            'filter[name]' => $message->name,
        ]));

        $response->assertOk();
        $response->assertViewIs('admin.reports.index');
        $response->assertViewHas('reportables', function ($reportables) use ($message) {
            return $reportables->contains(function ($reportable) use ($message) {
                return $reportable->reportable_type === Message::class && $reportable->reportable_id === $message->id;
            });
        });
    }

    private function createReportType(array $stase = []): ReportType
    {
        return ReportType::factory()
            ->state($stase + [
                'type' => ReportTypeEnum::Product->value,
            ])->create();
    }

    private function createProduct(array $state = []): Product
    {
        return Product::factory()
            ->hasReports(1)
            ->state($state + [
                'name' => 'Product A',
            ])
            ->create();
    }

    private function createMessage(array $state = []): Message
    {
        return Message::factory()
            ->hasReports(1)
            ->state($state + [
                'message' => 'This is a message.',
            ])
            ->create();
    }
}
