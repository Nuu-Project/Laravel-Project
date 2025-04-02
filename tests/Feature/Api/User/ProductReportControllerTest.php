<?php

namespace Tests\Feature\Api\User;

use App\Enums\ReportType as ReportTypeEnum;
use App\Http\Controllers\Api\User\ProductReportController;
use App\Models\Product;
use App\Models\Report;
use App\Models\ReportType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductReportControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_store_a_product_report_successfully(): void
    {
        // 建立一個測試用戶並登入
        $user = User::factory()->create();
        $this->actingAs($user);

        // 建立一個測試產品
        $product = Product::factory()->create();

        // 建立一個產品檢舉類型
        $reportType = ReportType::factory()->create(['type' => ReportTypeEnum::Product->value]);

        // 建立一個模擬的請求
        $request = new Request([
            'report_type_id' => $reportType->id,
            'description' => '這個產品有問題。',
        ]);

        // 建立控制器實例
        $controller = new ProductReportController;

        // 執行檢舉方法
        $response = $controller->store($request, $product);

        // 斷言回應是成功的 JSON
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['status' => 'success'], $response->getData(true));

        // 斷言資料庫中已建立檢舉記錄
        $this->assertDatabaseHas('reports', [
            'report_type_id' => $reportType->id,
            'user_id' => $user->id,
            'description' => '這個產品有問題。',
        ]);

        // 斷言檢舉記錄與產品建立了關聯
        $this->assertDatabaseHas('reportables', [
            'report_id' => Report::latest()->first()->id,
            'reportable_id' => $product->id,
            'reportable_type' => Product::class,
        ]);
    }

    #[Test]
    public function it_validates_report_type_id_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create();
        $request = new Request(['description' => '測試描述']);
        $controller = new ProductReportController;

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->store($request, $product);
    }

    #[Test]
    public function it_validates_report_type_id_exists(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create();
        $request = new Request(['report_type_id' => 999, 'description' => '測試描述']);
        $controller = new ProductReportController;

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->store($request, $product);
    }

    #[Test]
    public function it_validates_report_type_id_is_for_product(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create();
        // 建立一個非產品的檢舉類型
        $reportType = ReportType::factory()->create(['type' => ReportTypeEnum::Message->value]);
        $request = new Request(['report_type_id' => $reportType->id, 'description' => '測試描述']);
        $controller = new ProductReportController;

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->store($request, $product);
    }

    #[Test]
    public function it_validates_description_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create();
        $reportType = ReportType::factory()->create(['type' => ReportTypeEnum::Product->value]);
        $request = new Request(['report_type_id' => $reportType->id]);
        $controller = new ProductReportController;

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->store($request, $product);
    }

    #[Test]
    public function it_validates_description_max_length(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create();
        $reportType = ReportType::factory()->create(['type' => ReportTypeEnum::Product->value]);
        $longDescription = str_repeat('a', 256);
        $request = new Request(['report_type_id' => $reportType->id, 'description' => $longDescription]);
        $controller = new ProductReportController;

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->store($request, $product);
    }

    #[Test]
    public function it_prevents_duplicate_reports(): void
    {
        // 建立一個測試用戶並登入
        $user = User::factory()->create();
        $this->actingAs($user);

        // 建立一個測試產品
        $product = Product::factory()->create();

        // 建立一個產品檢舉類型
        $reportType = ReportType::factory()->create(['type' => ReportTypeEnum::Product->value]);

        // 建立一個已存在的檢舉
        $product->reports()->create([
            'report_type_id' => $reportType->id,
            'user_id' => $user->id,
            'description' => '第一次檢舉。',
        ]);

        // 建立一個模擬的請求，嘗試再次檢舉相同的產品和類型
        $request = new Request([
            'report_type_id' => $reportType->id,
            'description' => '第二次檢舉。',
        ]);

        // 建立控制器實例
        $controller = new ProductReportController;

        // 執行檢舉方法並預期會拋出驗證異常
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->store($request, $product);

        // 斷言資料庫中只有一條檢舉記錄
        $this->assertCount(1, Report::where('user_id', $user->id)->where('report_type_id', $reportType->id)->get());
    }
}
