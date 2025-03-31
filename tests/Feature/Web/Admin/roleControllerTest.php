<?php

namespace Tests\Feature\Web\Admin;

use App\Enums\RoleType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class RoleControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_admin_can_view_admin_users_list(){

        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);
        $adminUser1 = User::factory()->create()->assignRole($adminRole);
        $adminUser2 = User::factory()->create()->assignRole($adminRole);
        $adminUser3 = User::factory()->create()->assignRole($adminRole);

        $noadmin = User::factory()->create();

        $this->actingAs($adminUser1);

        $response = $this->get(route('admin.roles.index'));

        // 1. 驗證回應狀態碼是否為 200 (OK)
        $response->assertStatus(200);

        // 2. 驗證是否使用了 `admin.roles.index` 這個視圖
        $response->assertViewIs('admin.roles.index');

        // 3. 驗證視圖是否接收到了 `users` 這個變數
        $response->assertViewHas('users');

        // 4. 驗證 `$users` 變數是一個分頁實例 (LengthAwarePaginator)
        $users = $response->viewData('users');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $users);

        // 5. 驗證 `$users` 集合中是否包含我們建立的 admin 使用者
        $this->assertTrue($users->contains($adminUser3));
        $this->assertTrue($users->contains($adminUser2));

        // 6. 驗證 `$users` 集合中不包含非 admin 使用者
        $this->assertFalse($users->contains($noadmin));
    }

    public function test_admin_can_view_users_without_roles_page(){

        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);

        $adminUser1 = User::factory()->create()->assignRole($adminRole);
        $adminUser2 = User::factory()->create()->assignRole($adminRole);

        $User1 = User::factory()->create();
        $User2 = User::factory()->create();

        $this->actingAs($adminUser1);

        $response = $this->get(route('admin.roles.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.roles.create');
        $response->assertViewHas('users');

        $users = $response->viewData('users');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $users);

        $this->assertTrue($users->contains($User1));
        $this->assertTrue($users->contains($User2));

        $this->assertFalse($users->contains($adminUser2));
    }

    public function test_admin_can_find_users_without_roles_by_name_or_email(){
        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);

        $adminUser1 = User::factory()->create()->assignRole($adminRole);

        $userwithNameMatch = User::factory()->create(['name' => "john"]);
        $userwithEmailMatch = User::factory()->create(['email' => 'john@gamil.com']);
        $userWithout = User::factory()->create(['name' => 'ted']);
        $userhasRole = User::factory()->create()->assignRole($adminRole);

        $this->actingAs($adminUser1);

        $response = $this->get(route('admin.roles.create', ['filter' => ['name' => 'john']]));

        $response->assertStatus(200);
        $response->assertViewHas('users');

        $users = $response->viewData('users');

        $this->assertTrue($users->contains($userwithNameMatch));
        $this->assertTrue($users->contains($userwithEmailMatch));

        $this->assertFalse($users->contains($userWithout));
        $this->assertFalse($users->contains($userhasRole));
    }

    public function test_admin_can_assign_roles_to_users(){
        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);
        $adminUser = User::factory()->create()->assignRole($adminRole);

        // 确保在发送请求前，用户已登录
        $this->actingAs($adminUser);

        $users = User::factory()->count(2)->create();
        $userIds = $users->pluck('id')->toArray();

        $response = $this->post(route('admin.roles.store'), [
            'user_ids' => $userIds,
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success', '角色分配成功');

        foreach ($users as $user) {
            $freshUser = User::with('roles')->find($user->id);
            $this->assertTrue($freshUser->hasRole(RoleType::Admin->value()));
        }
    }

    public function test_admin_cannot_assign_role_without_user_ids()
    {
        // Arrange (準備階段)
        // 1. 確保 admin 角色存在
        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);

        // 2. 建立一個 admin 使用者並登入
        $adminUser = User::factory()->create()->assignRole($adminRole);
        $this->actingAs($adminUser);

        // Act (執行階段)
        // 1. 發送一個 POST 請求到 `admin.roles.store` 路由，但不包含 `user_ids` 參數
        $response = $this->post(route('admin.roles.store'), []);

        // Assert (驗證階段)
        // 1. 驗證回應是否重定向回上一個頁面 (通常是包含表單的頁面)
        $response->assertRedirect();

        // 2. 驗證 session 中是否包含 `user_ids` 的驗證錯誤
        $response->assertSessionHasErrors('user_ids');
    }

    public function test_admin_cannot_assign_role_if_user_ids_is_not_an_array()
    {
        // Arrange (準備階段)
        // 1. 確保 admin 角色存在
        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);

        // 2. 建立一個 admin 使用者並登入
        $adminUser = User::factory()->create()->assignRole($adminRole);
        $this->actingAs($adminUser);

        // Act (執行階段)
        // 1. 發送一個 POST 請求到 `admin.roles.store` 路由，並將 `user_ids` 設定為一個字串 (非陣列)
        $response = $this->post(route('admin.roles.store'), [
            'user_ids' => 'not an array',
        ]);

        // Assert (驗證階段)
        // 1. 驗證回應是否重定向回上一個頁面
        $response->assertRedirect();

        // 2. 驗證 session 中是否包含 `user_ids` 的驗證錯誤
        $response->assertSessionHasErrors('user_ids');
    }

    public function test_admin_cannot_assign_role_with_nonexistent_user_ids(){
        // Arrange (準備階段)
        // 1. 確保 admin 角色存在
        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);

        // 2. 建立一個 admin 使用者並登入
        $adminUser = User::factory()->create()->assignRole($adminRole);
        $this->actingAs($adminUser);

        // 3. 建立一個存在的使用者，我們將在請求中包含他的 ID
        $existingUser = User::factory()->create();

        // 4. 定義一個肯定不存在的使用者 ID (例如 999999)
        $nonExistentUserId = 999999;

        // Act (執行階段)
        // 1. 發送一個 POST 請求到 `admin.roles.store` 路由，並在 `user_ids` 陣列中包含一個存在和一個不存在的使用者 ID
        $response = $this->post(route('admin.roles.store'), [
            'user_ids' => [$existingUser->id, $nonExistentUserId],
        ]);

        // Assert (驗證階段)
        // 1. 驗證回應是否重定向回上一個頁面
        $response->assertRedirect();

        // 2. 驗證 session 中是否包含 `user_ids` 的驗證錯誤
        $response->assertSessionHasErrors('user_ids.*'); // 使用 `user_ids.*` 因為錯誤會針對陣列中的每個元素
    }

    public function test_admin_can_rmove_role_from_user(){
        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);

        $adminUser = User::factory()->create()->assignRole($adminRole);
        $adminUser1 = User::factory()->create()->assignRole($adminRole);
        $this->actingAs($adminUser);

        $response = $this->put(route('admin.roles.update', ['role' => 'admin']), [
            'selected_ids' => [$adminUser1->id],
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('success', '角色已成功移除');
        $adminUser1->refresh();
        $this->assertFalse($adminUser1->hasRole('admin'));
    }

    public function test_admin_cannot_remove_role_without_selected_ids()
    {
        // Arrange (準備階段)
        // 1. 確保 admin 角色存在
        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);

        // 2. 建立一個 admin 使用者並登入
        $adminUser = User::factory()->create()->assignRole($adminRole);
        $this->actingAs($adminUser);

        // Act (執行階段)
        // 1. 發送一個 PUT 請求到 `admin.roles.update` 路由，但不包含 `selected_ids` 參數
        $response = $this->put(route('admin.roles.update', ['role' => 'admin']), []);

        // Assert (驗證階段)
        // 1. 驗證回應是否重定向回上一個頁面
        $response->assertRedirect();

        // 2. 驗證 session 中是否包含 `selected_ids` 的驗證錯誤
        $response->assertSessionHasErrors('selected_ids');
    }

    public function test_admin_cannot_remove_role_if_selected_ids_is_not_an_array()
    {
        // Arrange (準備階段)
        // 1. 確保 admin 角色存在
        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);

        // 2. 建立一個 admin 使用者並登入
        $adminUser = User::factory()->create()->assignRole($adminRole);
        $this->actingAs($adminUser);

        // Act (執行階段)
        // 1. 發送一個 PUT 請求到 `admin.roles.update` 路由，並將 `selected_ids` 設定為一個字串 (非陣列)
        $response = $this->put(route('admin.roles.update', ['role' => 'admin']), [
            'selected_ids' => 'not an array',
        ]);

        // Assert (驗證階段)
        // 1. 驗證回應是否重定向回上一個頁面
        $response->assertRedirect();

        // 2. 驗證 session 中是否包含 `selected_ids` 的驗證錯誤
        $response->assertSessionHasErrors('selected_ids');
    }

    public function test_admin_cannot_remove_role_with_nonexistent_user_ids()
    {
        // Arrange (準備階段)
        // 1. 確保 admin 角色存在
        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);

        // 2. 建立一個 admin 使用者並登入
        $adminUser = User::factory()->create()->assignRole($adminRole);
        $this->actingAs($adminUser);

        // 3. 建立一個存在的使用者，我們將在請求中包含他的 ID
        $existingUser = User::factory()->create();

        // 4. 定義一個肯定不存在的使用者 ID (例如 999999)
        $nonExistentUserId = 999999;

        // Act (執行階段)
        // 1. 發送一個 PUT 請求到 `admin.roles.update` 路由，並在 `selected_ids` 陣列中包含一個存在和一個不存在的使用者 ID
        $response = $this->put(route('admin.roles.update', ['role' => 'admin']), [
            'selected_ids' => [$existingUser->id, $nonExistentUserId],
        ]);

        // Assert (驗證階段)
        // 1. 驗證回應是否重定向回上一個頁面
        $response->assertRedirect();

        // 2. 驗證 session 中是否包含 `selected_ids` 的驗證錯誤
        $response->assertSessionHasErrors('selected_ids.*'); // 使用 `selected_ids.*` 因為錯誤會針對陣列中的每個元素
    }

    public function test_admin_cannot_remove_their_own_role()
    {
        // Arrange (準備階段)
        // 1. 確保 admin 角色存在
        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);

        // 2. 建立一個 admin 使用者並登入
        $adminUser = User::factory()->create()->assignRole($adminRole);
        $this->actingAs($adminUser);

        // Act (執行階段)
        // 1. 發送一個 PUT 請求到 `admin.roles.update` 路由，嘗試移除 'admin' 角色，並將當前管理員的 ID 包含在 `selected_ids` 中
        $response = $this->put(route('admin.roles.update', ['role' => 'admin']), [
            'selected_ids' => [$adminUser->id],
        ]);

        // Assert (驗證階段)
        // 1. 驗證回應是否重定向到 `admin.roles.index` 路由
        $response->assertRedirect(route('admin.roles.index'));

        // 2. 重新從資料庫載入管理員模型
        $adminUser->refresh();

        // 3. 驗證管理員仍然擁有 'admin' 角色
        $this->assertTrue($adminUser->hasRole(RoleType::Admin));

        // 4. 驗證 session 中沒有錯誤訊息 (因為目前的程式碼只是跳過)
        $response->assertSessionMissing('error');
        // 如果你的程式碼修改為返回錯誤訊息，你需要改為驗證是否有錯誤訊息
        // 例如：$response->assertSessionHas('error', '你不能移除自己的管理員角色');
    }

    public function test_admin_can_try_to_remove_role_from_user_without_role()
    {
        // Arrange (準備階段)
        // 1. 確保 admin 角色存在
        $adminRole = Role::firstOrCreate(['name' => RoleType::Admin->value()]);

        // 2. 建立一個 admin 使用者並登入
        $adminUser = User::factory()->create()->assignRole($adminRole);
        $this->actingAs($adminUser);

        // 3. 建立一個沒有 'editor' 角色的普通使用者
        $userWithoutRole = User::factory()->create();

        // Act (執行階段)
        // 1. 發送一個 PUT 請求到 `admin.roles.update` 路由，嘗試移除 'editor' 角色，並將 `$userWithoutRole` 的 ID 包含在 `selected_ids` 中
        $response = $this->put(route('admin.roles.update', ['role' => 'admin']), [
            'selected_ids' => [$userWithoutRole->id],
        ]);

        // Assert (驗證階段)
        // 1. 驗證回應是否重定向到 `admin.roles.index` 路由
        $response->assertRedirect(route('admin.roles.index'));

        // 2. 驗證 session 中是否包含成功的 flash 訊息 (因為程式碼中即使沒有移除任何角色也會返回成功訊息)
        $response->assertSessionHas('success', '角色已成功移除');

        // 3. 重新從資料庫載入使用者模型
        $userWithoutRole->refresh();

        // 4. 驗證使用者仍然沒有 'editor' 角色
        $this->assertFalse($userWithoutRole->hasRole('admin'));
    }
}


