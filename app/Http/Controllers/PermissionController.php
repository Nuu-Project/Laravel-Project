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

        if ($adminRole) {
            // 移除 'admin' 角色的權限
            $adminRole->revokePermissionTo('manage users');
            $adminRole->revokePermissionTo('edit articles');

            // 將 'admin' 角色從該用戶移除
            $user->removeRole($adminRole);

            return redirect()->route('role_permissions.index')->with('success', 'Admin role removed successfully.');
        } else {
            return redirect()->route('role_permissions.index')->with('error', 'Admin role does not exist.');
        }
    }
}
