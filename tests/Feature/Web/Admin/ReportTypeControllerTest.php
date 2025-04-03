<?php

namespace Tests\Feature\Web\Admin;

use App\Enums\RoleType;
use App\Models\ReportType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 初始化 admin 角色
        Role::create(['name' => RoleType::Admin->value()]);
    }

    private function actingAsAdmin()
    {
        $admin = User::factory()->create();
        $admin->assignRole(RoleType::Admin->value());
        $this->actingAs($admin);
    }

    public function test_admin_can_view_report_types_index()
    {
        $this->actingAsAdmin();

        // 創建測試資料
        ReportType::factory()->count(5)->create();

        $response = $this->get(route('admin.report-types.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.report-types.index');
        $response->assertViewHas('reportTypes');
    }

    public function test_admin_can_create_report_type()
    {
        $this->actingAsAdmin();

        $data = [
            'name' => '測試類型',
            'type' => 'product',
            'order_column' => 1,
        ];

        $response = $this->post(route('admin.report-types.store'), $data);

        $response->assertRedirect(route('admin.report-types.index'));
        $response->assertSessionHas('message', '檢舉類型新增成功！');

        $this->assertDatabaseHas('report_types', [
            'name->zh_TW' => '測試類型',
            'type' => $data['type'],
            'order_column' => $data['order_column'],
        ]);
    }

    public function test_admin_can_edit_report_type()
    {
        $this->actingAsAdmin();

        $reportType = ReportType::factory()->create();

        $response = $this->get(route('admin.report-types.edit', $reportType->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.report-types.edit');
        $response->assertViewHas('reportType', $reportType);
    }

    public function test_admin_can_update_report_type()
    {
        $this->actingAsAdmin();

        $reportType = ReportType::factory()->create([
            'name' => ['zh_TW' => '舊名稱'],
        ]);

        $data = [
            'name' => '新名稱',
            'type' => $reportType->type,
            'order_column' => $reportType->order_column + 1,
        ];

        $response = $this->put(route('admin.report-types.update', $reportType->id), $data);

        $response->assertRedirect(route('admin.report-types.index'));
        $response->assertSessionHas('message', '檢舉類型更新成功！');

        $this->assertDatabaseHas('report_types', [
            'id' => $reportType->id,
            'name->zh_TW' => '新名稱',
            'order_column' => $data['order_column'],
        ]);
    }

    public function test_admin_can_delete_report_type()
    {
        $this->actingAsAdmin();

        $reportType = ReportType::factory()->create();

        $response = $this->delete(route('admin.report-types.destroy', $reportType->id));

        $response->assertRedirect(route('admin.report-types.index'));
        $response->assertSessionHas('success', '檢舉類型已刪除');

        $this->assertSoftDeleted($reportType);
    }

    public function test_admin_can_restore_report_type()
    {
        $this->actingAsAdmin();

        $reportType = ReportType::factory()->create();
        $reportType->delete();

        $response = $this->post(route('admin.report-types.restore', $reportType->id));

        $response->assertRedirect(route('admin.report-types.index'));
        $response->assertSessionHas('success', '檢舉類型已恢復');

        $this->assertDatabaseHas('report_types', [
            'id' => $reportType->id,
            'deleted_at' => null,
        ]);
    }

    public function test_non_admin_cannot_access_routes()
    {
        // 沒有登入時
        $response = $this->get(route('admin.report-types.index'));
        $response->assertRedirect(route('login'));

        // 登入非 admin 使用者
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.report-types.index'));
        $response->assertStatus(403);
    }
}
