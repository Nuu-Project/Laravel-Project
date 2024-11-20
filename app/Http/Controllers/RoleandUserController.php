<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RoleandUserController extends Controller
{
    public function index()
    {
        $users = User::role(['admin', 'user'])->paginate(10);
        return view('admin.roles.index', compact('users'));
    }

    public function create(Request $request)
    {
        // 獲取未分配角色的用戶
        $users = User::whereDoesntHave('roles')->get();
        
        // 獲取要分配的角色類型（admin 或 user）
        $type = $request->query('type');
        
        return view('admin.roles.create', compact('users', 'type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role_type' => 'required|in:admin,user'
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        
        foreach ($users as $user) {
            $user->assignRole($request->role_type);
        }

        return response()->json([
            'message' => '角色分配成功',
            'redirect' => route('admin.roles.index')
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.roles.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user = User::findOrFail($id);
        
        // 移除現有角色並分配新角色
        $user->syncRoles([$request->role]);

        return response()->json([
            'message' => '角色更新成功',
            'redirect' => route('admin.roles.index')
        ]);
    }
}