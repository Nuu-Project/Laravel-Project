<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 方法示例
    public function index()
{
    $users = User::all();
    return view('admin.user', compact('users'));
}

    public function show($id)
    {
        // 显示特定用户的信息
    }

    public function create()
    {
        // 显示创建用户的表单
    }

    public function store(Request $request)
    {
        // 处理保存新用户的逻辑
    }

    public function edit($id)
    {
        // 显示编辑用户的表单
    }

    public function update(Request $request, $id)
    {
        // 处理更新用户的逻辑
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // 刪除用戶相關的評論
        $user->chirps()->delete();

        // 刪除用戶
        $user->delete();

        return redirect()->route('users.index')->with('success', '用戶及其相關評論已成功刪除');
    }
}
