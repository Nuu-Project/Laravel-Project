<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\RoleType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    public function index(): View
    {
        $users = User::role(['admin'])->paginate(10);

        return view('admin.roles.index', [$users => 'users']);
    }

    public function create(Request $request): View
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

        return view('admin.roles.create', [$users => 'users']);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $roleType = RoleType::Admin;
        $users = User::whereIn('id', $validated['user_ids'])->get();

        foreach ($users as $user) {
            $user->assignRole($roleType);
        }

        return redirect()->route('admin.roles.index')->with('success', '角色分配成功');
    }

    // 更新角色的方法
    public function update(Request $request, $role): RedirectResponse
    {
        $request->validate([
            'selected_ids' => ['required', 'array'],
            'selected_ids.*' => ['exists:users,id'],
        ]);

        $users = User::whereIn('id', $request->selected_ids)->get();
        $currentUserId = auth()->id(); // 獲取當前登入管理員 ID

        foreach ($users as $user) {
            // 如果是當前管理員，跳過不移除角色
            if ($user->id == $currentUserId) {
                continue; // 或者 return back()->with('error', '你不能移除自己的管理員角色');
            }

            // 檢查用戶是否有這個角色，並移除該角色
            if ($user->hasRole($role)) {
                $user->removeRole($role);
            }
        }

        return redirect()->route('admin.roles.index')->with('success', '角色已成功移除');
    }
}
