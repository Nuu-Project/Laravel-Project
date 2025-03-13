<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSuspendController extends Controller
{
    public function suspend(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'duration' => ['required', 'integer', 'min:0'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $user->time_limit = now()->addSeconds($request->integer('duration'));
        $user->suspend_reason = $request->input('reason'); // 保存暫停原因
        $user->save();

        return response()->json(['message' => '用戶已成功暫停']);
    }
}
