<?php

namespace Tests\Feature\Web\Admin;

use App\Enums\ReportType as ReportTypeEnum;
use App\Enums\RoleType;
use App\Models\Message;
use App\Models\Product;
use App\Models\ReportType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 使用 ReportTypeFactory 建立類型資料
        ReportType::factory()->create(['type' => ReportTypeEnum::Product->value()]);
        ReportType::factory()->create(['type' => ReportTypeEnum::Message->value()]);

        // 初始化 admin 角色
        $this->actingAsAdmin();
    }

/*************  ✨ Windsurf Command ⭐  *************/
/*******  8141604c-f555-489e-b99f-87f0381cdbea  *******/
    public function test_index_displays_filtered_reportables_by_type()
    {
        // 創建管理員並登入
        $admin = User::factory()->create();
        $admin->assignRole(RoleType::Admin->value()); // 指派 admin 角色
        $this->actingAs($admin);

        // 創建產品和訊息
        $product = Product::factory()->withReports(1)->create(['name' => 'Product A']);
        $message = Message::factory()->withReports(1)->create(['message' => 'This is a message.']);

        // 測試篩選產品類型
        $response = $this->get(route('admin.reports.index', ['filter[type]' => ReportTypeEnum::Product->value()]));

        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.index');
        $response->assertViewHas('reportables', function ($reportables) use ($product) {
            return $reportables->contains(function ($reportable) use ($product) {
                return $reportable->reportable_type === Product::class && $reportable->reportable_id === $product->id;
            });
        });

        // 測試篩選訊息類型
        $response = $this->get(route('admin.reports.index', ['filter[type]' => ReportTypeEnum::Message->value()]));

        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.index');
        $response->assertViewHas('reportables', function ($reportables) use ($message) {
            return $reportables->contains(function ($reportable) use ($message) {
                return $reportable->reportable_type === Message::class && $reportable->reportable_id === $message->id;
            });
        });
    }

    public function test_index_displays_filtered_reportables_by_name()
    {
        // 創建管理員並登入
        $admin = User::factory()->create();
        $admin->assignRole(RoleType::Admin->value()); // 指派 admin 角色
        $this->actingAs($admin);

        // 創建產品和訊息
        $product = Product::factory()->withReports(1)->create(['name' => 'Specific Product']);
        $message = Message::factory()->withReports(1)->create(['message' => 'Specific Message']);

        // 測試篩選產品名稱
        $response = $this->get(route('admin.reports.index', [
            'filter[type]' => ReportTypeEnum::Product->value(),
            'filter[name]' => 'Specific Product',
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.index');
        $response->assertViewHas('reportables', function ($reportables) use ($product) {
            return $reportables->contains(function ($reportable) use ($product) {
                return $reportable->reportable_type === Product::class && $reportable->reportable_id === $product->id;
            });
        });

        // 測試篩選訊息內容
        $response = $this->get(route('admin.reports.index', [
            'filter[type]' => ReportTypeEnum::Message->value(),
            'filter[name]' => 'Specific Message',
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.index');
        $response->assertViewHas('reportables', function ($reportables) use ($message) {
            return $reportables->contains(function ($reportable) use ($message) {
                return $reportable->reportable_type === Message::class && $reportable->reportable_id === $message->id;
            });
        });
    }
}
