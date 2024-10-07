<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 方法示例
    public function index()
    {
        // 获取所有用户
        $users = User::all();

        // 返回视图并传递用户数据
        return view('users.index', compact('users'));
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
        $user->delete(); // 软删除用户
        return redirect()->route('users.index')->with('success', '用户已删除');
    }
}

