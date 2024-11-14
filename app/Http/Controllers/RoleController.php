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
            return redirect()->route('admin.roles.index')->with('error', '指定的角色不存在');
        }

        // 為每個選中的用戶分配該角色
        foreach ($request['users'] as $userId) {
            $user = User::find($userId);  // 查找用戶
            if ($user) {
                // 分配角色給該用戶
                $user->assignRole($role->name);
            }
        }

        // 返回角色頁面，並預加載用戶資料
        $roles = Role::with('users')->get();  // 只預加載 users 關聯資料

        return redirect()->route('admin.roles.index')->with('roles', $roles)->with('success', '角色已成功分配給用戶');
    }
}
