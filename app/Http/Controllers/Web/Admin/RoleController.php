<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\RoleType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::role(['admin'])->paginate(10);

        return view('admin.roles.index', compact('users'));
    }

    public function create(Request $request)
    {
        $users = QueryBuilder::for(User::class)
            ->whereDoesntHave('roles')
            ->allowedFilters([
                AllowedFilter::callback('name', function (Builder $query, string $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    });
                }),
            ])
            ->paginate(10)
            ->withQueryString();

        return view('admin.roles.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $roleType = RoleType::Admin;
        $users = User::whereIn('id', $validated['user_ids'])->get();

        foreach ($users as $user) {
            $user->assignRole($roleType);
        }

        return redirect()->route('admin.roles.index')->with('success', '角色分配成功');
    }

    // 更新角色的方法
    public function update(Request $request, $role)
    {
        // 驗證選中的角色
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:users,id', // 確保用戶 ID 存在
        ]);

        // 迭代選中的用戶 ID 並解除角色
        $users = User::whereIn('id', $request->selected_ids)->get();
        foreach ($users as $user) {
            // 檢查用戶是否有這個角色，並移除該角色
            if ($user->hasRole($role)) {
                $user->removeRole($role); // 移除角色
            }
        }

        // 成功後重定向並顯示訊息
        return redirect()->route('admin.roles.index')->with('success', '角色已成功移除');
    }
}
