<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    // 方法示例
    public function index()
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::callback('name', function (Builder $query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    });
                }),
            ])
            ->paginate(10)
            ->withQueryString();

        return view('admin.user', compact('users'));
    }

    public function suspend(Request $request, User $user)
    {
        $validated = $request->validate([
            'duration' => ['required', 'integer', 'min:0'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $user->time_limit = now()->addSeconds($request->integer('duration'));
        $user->save();

        return response()->json(['message' => '用戶已成功暫停']);
    }
}
