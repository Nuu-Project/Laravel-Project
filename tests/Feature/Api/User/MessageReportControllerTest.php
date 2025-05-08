<?php

namespace Tests\Feature\Api\User;

use App\Enums\ReportType as ReportTypeEnum;
use App\Models\Message;
use App\Models\ReportType;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class MessageReportControllerTest extends TestCase
{
    private Message $message;

    private ReportType $reportType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();
        $this->message = Message::factory()->create();
        $this->reportType = $this->createReportType();
    }

    public function test_successful_message_report_creation(): void
    {
        $response = $this->postJson(route('api.messages.reports.store', $this->message), [
            'report_type_id' => $this->reportType->id,
            'description' => '這則留言有問題。',
        ]);

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('status', 'success')->etc());

        $this->assertDatabaseHas('reports', [
            'report_type_id' => $this->reportType->id,
            'user_id' => auth()->id(),
            'description' => '這則留言有問題。',
        ]);

        $this->assertDatabaseHas('reportables', [
            'reportable_id' => $this->message->id,
            'reportable_type' => Message::class,
        ]);
    }

    public function test_validation_errors_for_missing_report_type_id(): void
    {
        $response = $this->postJson(route('api.messages.reports.store', $this->message), [
            'description' => '測試描述',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['report_type_id']);
    }

    public function test_validation_errors_for_invalid_report_type_id(): void
    {
        $response = $this->postJson(route('api.messages.reports.store', $this->message), [
            'report_type_id' => 999,
            'description' => '測試描述',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['report_type_id']);
    }

    public function test_validation_errors_for_wrong_report_type_id(): void
    {
        $invalidReportType = $this->createReportType([
            'type' => ReportTypeEnum::Product->value,
        ]);

        $response = $this->postJson(route('api.messages.reports.store', $this->message), [
            'report_type_id' => $invalidReportType->id,
            'description' => '測試描述',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['report_type_id']);
    }

    public function test_validation_errors_for_missing_description(): void
    {
        $response = $this->postJson(route('api.messages.reports.store', $this->message), [
            'report_type_id' => $this->reportType->id,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['description']);
    }

    public function test_validation_errors_for_description_exceeding_max_length(): void
    {
        $response = $this->postJson(route('api.messages.reports.store', $this->message), [
            'report_type_id' => $this->reportType->id,
            'description' => str_repeat('a', 256),
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['description']);
    }

    public function test_updates_description_for_duplicate_reports(): void
    {
        $this->message->reports()->create($this->getReportData('第一次檢舉。'));

        $this->reportsMessage($this->getReportData('第二次檢舉。'))
            ->assertOk()
            ->assertJson([
                'status' => 'updated',
            ]);

        $this->assertDatabaseHas('reports', $this->getReportData('第二次檢舉。'));

        $this->assertCount(1, $this->message->reports()->where('report_type_id', $this->reportType->id)->where('user_id', auth()->id())->get());
    }

    private function reportsMessage(array $data): \Illuminate\Testing\TestResponse
    {
        return $this->postJson(route('api.messages.reports.store', $this->message), $data);
    }

    private function createReportType(array $state = []): ReportType
    {
        return ReportType::factory()
            ->state($state + [
                'type' => ReportTypeEnum::Message->value,
            ])->create();
    }

    private function getReportData(string $description): array
    {
        return [
            'report_type_id' => $this->reportType->id,
            'user_id' => auth()->id(),
            'description' => $description,
        ];
    }
}
