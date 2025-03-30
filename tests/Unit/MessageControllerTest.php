<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 建立角色和權限
        $adminRole = Role::create(['name' => 'admin']);
        $viewMessagesPermission = Permission::create(['name' => 'view messages']);

        // 將權限授予角色
        $adminRole->givePermissionTo($viewMessagesPermission);
    }

    /** @test */
    public function admin_can_view_messages_page()
    {
        /** @var \App\Models\User $admin */
        // 建立管理員帳號
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 指派管理員角色並給予權限
        $admin->assignRole('admin');

        // 以管理員身份登入
        $this->actingAs($admin);

        // 測試進入訊息頁面
        $response = $this->get(route('admin.messages.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_see_messages_on_index_page()
    {
        /** @var \App\Models\User $user */
        // 建立使用者與訊息
        $user = User::factory()->create(['name' => 'John Doe']);
        $message = Message::factory()->create(['user_id' => $user->id]);

        // 指派管理員角色並給予權限
        $user->assignRole('admin');

        // 以管理員身份登入
        $this->actingAs($user);

        // 測試進入訊息頁面
        $response = $this->get(route('admin.messages.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.messages.index');
        $response->assertSee($message->content);
    }

    /** @test */
    public function admin_can_filter_messages_by_user_name()
    {
        /** @var \App\Models\User $user1 */
        // 建立兩位使用者與各自的訊息
        $user1 = User::factory()->create(['name' => 'Alice']);
        $user2 = User::factory()->create(['name' => 'Bob']);

        $message1 = Message::factory()->create(['user_id' => $user1->id]);
        $message2 = Message::factory()->create(['user_id' => $user2->id]);

        // 指派管理員角色
        $user1->assignRole('admin');

        // 以管理員身份登入
        $this->actingAs($user1);

        // 測試根據使用者名稱篩選訊息
        $response = $this->get(route('admin.messages.index', ['filter[name]' => 'Alice']));

        $response->assertStatus(200);
        $response->assertSee($message1->content);
        $response->assertDontSee($message2->content);
    }
}
