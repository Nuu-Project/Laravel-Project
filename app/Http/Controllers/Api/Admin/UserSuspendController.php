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
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => '無法停用自己'], 403);
        }

        $validatedData = $request->validate([
            'duration' => ['required', 'integer', 'min:0'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'time_limit' => now()->addSeconds((int) $validatedData['duration']),
            'suspend_reason' => $validatedData['reason'] ?? null,
        ]);

        return response()->json(['message' => '用戶已成功暫停']);
    }
}
