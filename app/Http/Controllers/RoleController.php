<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::select('name', 'id')->get();
        $roles = Role::whereIn('id', [1, 2, 3])
            ->select('id', 'name')
            ->get();

        return view('admin.role', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        // 根據角色名稱查找現有角色
        $role = Role::where('name', $request['role_name'])->first();

        // 如果角色不存在，則返回錯誤
        if (! $role) {
            return redirect()->route('admin.role.index');
        }

        // 為每個選中的用戶分配該角色
        foreach ($request['users'] as $userId) {
            $user = User::find($userId);  // 查找用戶
            if ($user) {
                // 分配角色給該用戶
                $user->assignRole($role->name);
            }
        }

        return redirect()->route('admin.role.index');
    }

    public function edit($role)
    {
        // 獲取角色資料
        $role = Role::with('users')->findOrFail($role);

        // 獲取所有用戶
        $users = User::select('name', 'id')->get();

        // 返回編輯視圖，並傳遞角色和所有用戶資料
        return view('admin.role_edit', compact('role', 'users'));
    }

    public function update(Request $request, $role)
    {
        // 根據角色 ID 查找角色
        $role = Role::findOrFail($role);

        // 獲取目前與該角色相關聯的所有用戶
        $currentUsers = $role->users()->pluck('id')->toArray();

        // 從表單獲取選中的用戶 ID
        $selectedUserIds = $request->input('users', []);

        // 1. 找出那些在當前關聯中，但不在新選擇中的用戶，並移除與角色的關聯
        $usersToDetach = array_diff($currentUsers, $selectedUserIds);
        if (!empty($usersToDetach)) {
            $role->users()->detach($usersToDetach);  // 移除這些用戶的角色關聯
        }

        // 2. 重新分配角色給表單中選中的用戶
        foreach ($selectedUserIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                // 為用戶重新分配角色
                $user->assignRole($role->name);
            }
        }

        // 返回角色頁面並顯示成功消息
        return redirect()->route('admin.role.index');
    }
}
