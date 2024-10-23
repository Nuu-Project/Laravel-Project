<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class PermissionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create($userId)
    {
        // 找到對應的用戶
        $user = User::findOrFail($userId);

        // 確認 'admin' 角色是否存在，否則創建它
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // 為這個角色創建一些權限，例如 'manage users', 'edit articles' 等
        $manageUsersPermission = Permission::firstOrCreate(['name' => 'manage users']);
        $editArticlesPermission = Permission::firstOrCreate(['name' => 'edit articles']);

        // 將這些權限分配給 'admin' 角色
        $adminRole->givePermissionTo($manageUsersPermission);
        $adminRole->givePermissionTo($editArticlesPermission);

        // 將 'admin' 角色分配給該用戶
        $user->assignRole($adminRole);

        return redirect()->route('role_permissions.index')->with('success', 'User has been made an admin.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($userId)
    {
        // 找到對應的用戶
        $user = User::findOrFail($userId);

        // 確認 'admin' 角色是否存在
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole && $user->hasRole('admin')) {  // 使用角色名称字符串
            // 移除 'admin' 角色的權限
            $user->removeRole('admin');  // 使用角色名称字符串

            // 確保用戶有 'user' 角色
            $userRole = Role::firstOrCreate(['name' => 'user']);
            $user->assignRole('user');  // 使用角色名称字符串

            return redirect()->back()->with('success', '管理員權限已成功移除。');
        } else {
            // 如果用户不是管理员，则赋予管理员权限
            $user->assignRole('admin');

            return redirect()->back()->with('success', '管理員權限已成功添加。');
        }
    }
}
