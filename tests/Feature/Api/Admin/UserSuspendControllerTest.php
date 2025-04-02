<?php

namespace Tests\Feature\Api\Admin;

use App\Http\Controllers\Api\Admin\UserSuspendController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserSuspendControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_suspend_a_user_successfully(): void
    {
        // 建立一個測試用戶
        $user = User::factory()->create();

        // 建立一個模擬的請求
        $request = new Request([
            'duration' => 3600, // 暫停 1 小時
            'reason' => '違反社群規範',
        ]);

        // 建立控制器實例
        $controller = new UserSuspendController;

        // 執行暫停方法
        $response = $controller->suspend($request, $user);

        // 斷言回應是成功的 JSON
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => '用戶已成功暫停'], $response->getData(true));

        // 重新載入用戶模型以檢查資料庫是否已更新
        $user->refresh();

        // 斷言用戶的 time_limit 已正確設定
        $expectedTimeLimit = now()->addSeconds(3600);
        $this->assertEquals($expectedTimeLimit->toDateTimeString(), $user->time_limit->toDateTimeString());

        // 斷言用戶的 suspend_reason 已正確設定
        $this->assertEquals('違反社群規範', $user->suspend_reason);
    }

    #[Test]
    public function it_can_suspend_a_user_without_a_reason(): void
    {
        // 建立一個測試用戶
        $user = User::factory()->create();

        // 建立一個模擬的請求，沒有 reason
        $request = new Request([
            'duration' => 7200, // 暫停 2 小時
        ]);

        // 建立控制器實例
        $controller = new UserSuspendController;

        // 執行暫停方法
        $response = $controller->suspend($request, $user);

        // 斷言回應是成功的 JSON
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => '用戶已成功暫停'], $response->getData(true));

        // 重新載入用戶模型以檢查資料庫是否已更新
        $user->refresh();

        // 斷言用戶的 time_limit 已正確設定
        $expectedTimeLimit = now()->addSeconds(7200);
        $this->assertEquals($expectedTimeLimit->toDateTimeString(), $user->time_limit->toDateTimeString());

        // 斷言用戶的 suspend_reason 是 null
        $this->assertNull($user->suspend_reason);
    }

    #[Test]
    public function it_validates_the_duration_field_is_required(): void
    {
        // 建立一個測試用戶
        $user = User::factory()->create();

        // 建立一個模擬的請求，沒有 duration
        $request = new Request([
            'reason' => '測試原因',
        ]);

        // 建立控制器實例
        $controller = new UserSuspendController;

        // 執行暫停方法並預期會拋出驗證異常
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $controller->suspend($request, $user);
    }

    #[Test]
    public function it_validates_the_duration_field_is_an_integer(): void
    {
        // 建立一個測試用戶
        $user = User::factory()->create();

        // 建立一個模擬的請求，duration 不是整數
        $request = new Request([
            'duration' => 'not an integer',
            'reason' => '測試原因',
        ]);

        // 建立控制器實例
        $controller = new UserSuspendController;

        // 執行暫停方法並預期會拋出驗證異常
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $controller->suspend($request, $user);
    }

    #[Test]
    public function it_validates_the_duration_field_is_not_negative(): void
    {
        // 建立一個測試用戶
        $user = User::factory()->create();

        // 建立一個模擬的請求，duration 是負數
        $request = new Request([
            'duration' => -10,
            'reason' => '測試原因',
        ]);

        // 建立控制器實例
        $controller = new UserSuspendController;

        // 執行暫停方法並預期會拋出驗證異常
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $controller->suspend($request, $user);
    }

    #[Test]
    public function it_validates_the_reason_field_is_a_string_and_max_length(): void
    {
        // 建立一個測試用戶
        $user = User::factory()->create();

        // 建立一個模擬的請求，reason 超過最大長度
        $longReason = str_repeat('a', 256);
        $request = new Request([
            'duration' => 3600,
            'reason' => $longReason,
        ]);

        // 建立控制器實例
        $controller = new UserSuspendController;

        // 執行暫停方法並預期會拋出驗證異常
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $controller->suspend($request, $user);
    }
}
