<?php

namespace Tests\Feature\Api\Admin;

use App\Models\User;
use Tests\TestCase;

class UserSuspendControllerTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin();

        $this->user = User::factory()->create();
    }

    public function test_user_can_be_suspended_with_reason(): void
    {
        $this->suspendUser([
            'duration' => 3600,
            'reason' => '違反社群規範',
        ])->assertOk()
            ->assertJson([
                'message' => '用戶已成功暫停',
            ]);

        $this->user->refresh();

        $expectedTimeLimit = now()->addSeconds(3600);
        $this->assertEquals($expectedTimeLimit->toDateTimeString(), $this->user->time_limit->toDateTimeString());

        $this->assertEquals('違反社群規範', $this->user->suspend_reason);
    }

    public function test_user_can_be_suspended_without_reason(): void
    {
        $this->suspendUser([
            'duration' => 7200,
        ])->assertOk()
            ->assertJson([
                'message' => '用戶已成功暫停',
            ]);

        $this->user->refresh();

        $expectedTimeLimit = now()->addSeconds(7200);
        $this->assertEquals($expectedTimeLimit->toDateTimeString(), $this->user->time_limit->toDateTimeString());

        $this->assertNull($this->user->suspend_reason);
    }

    public function test_duration_is_required(): void
    {
        $this->suspendUser([
            'reason' => '測試原因',
        ])->assertStatus(422)
            ->assertJsonValidationErrors('duration');
    }

    public function test_duration_must_be_integer(): void
    {
        $this->suspendUser([
            'duration' => 'not an integer',
            'reason' => '測試原因',
        ])->assertStatus(422)
            ->assertJsonValidationErrors('duration');
    }

    public function test_duration_must_not_be_negative(): void
    {
        $this->suspendUser([
            'duration' => -10,
            'reason' => '測試原因',
        ])->assertStatus(422)
            ->assertJsonValidationErrors('duration');
    }

    public function test_reason_must_be_under_255_characters(): void
    {
        $longReason = str_repeat('a', 256);

        $this->suspendUser([
            'duration' => 3600,
            'reason' => $longReason,
        ])->assertStatus(422)
            ->assertJsonValidationErrors('reason');
    }

    private function suspendUser(array $data): \Illuminate\Testing\TestResponse
    {
        return $this->postJson(route('admin.users.suspend', $this->user), $data);
    }
}
