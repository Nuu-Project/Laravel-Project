<?php

namespace Tests\Feature\Web\Admin;

use App\Models\ReportType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    private ReportType $reportType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reportType = ReportType::factory()->create();

        $this->actingAsAdmin();
    }

    public function test_admin_can_view_report_types_index()
    {
        $this->get(route('admin.report-types.index'))
            ->assertOk()
            ->assertViewIs('admin.report-types.index')
            ->assertViewHas('reportTypes');
    }

    public function test_admin_can_create_report_type()
    {
        $data = [
            'name' => '測試類型',
            'type' => 'product',
            'order_column' => 1,
        ];

        $this->post(route('admin.report-types.store'), $data)
            ->assertRedirect(route('admin.report-types.index'))
            ->assertSessionHas('message', '檢舉類型新增成功！');

        $this->assertDatabaseHas('report_types', [
            'name->zh_TW' => $data['name'],
            'type' => $data['type'],
            'order_column' => $data['order_column'],
        ]);
    }

    public function test_admin_can_edit_report_type()
    {

        $this->get(route('admin.report-types.edit', $this->reportType->id))
            ->assertOk()
            ->assertViewIs('admin.report-types.edit')
            ->assertViewHas('reportType', $this->reportType);
    }

    public function test_admin_can_update_report_type()
    {
        $data = [
            'name' => '新名稱',
            'type' => $this->reportType->type,
            'order_column' => $this->reportType->order_column + 1,
        ];

        $this->put(route('admin.report-types.update', $this->reportType->id), $data)
            ->assertRedirect(route('admin.report-types.index'))
            ->assertSessionHas('message', '檢舉類型更新成功！');

        $this->assertDatabaseHas('report_types', [
            'id' => $this->reportType->id,
            'name->zh_TW' => $data['name'],
            'order_column' => $data['order_column'],
        ]);
    }

    public function test_admin_can_delete_report_type()
    {

        $this->delete(route('admin.report-types.destroy', $this->reportType->id))
            ->assertRedirect(route('admin.report-types.index'))
            ->assertSessionHas('success', '檢舉類型已刪除');

        $this->assertSoftDeleted($this->reportType);
    }

    public function test_admin_can_restore_report_type()
    {

        $this->reportType->delete();

        $this->patch(route('admin.report-types.restore', $this->reportType->id))
            ->assertRedirect(route('admin.report-types.index'))
            ->assertSessionHas('success', '檢舉類型已恢復');

        $this->assertDatabaseHas('report_types', [
            'id' => $this->reportType->id,
            'deleted_at' => null,
        ]);
    }

    public function test_non_admin_cannot_access_routes()
    {
        $this->logout();

        $this->get(route('admin.report-types.index'))
            ->assertRedirect(route('login'));

        $this->actingAsUser();

        $this->get(route('admin.report-types.index'))
            ->assertForbidden();
    }
}
