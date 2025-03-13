<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MessageReportController extends Controller
{
    public function store(Request $request, Message $message): JsonResponse
    {
        $request->validate([
            'report_type_id' => [
                'required',
                'exists:report_types,id',
                Rule::unique('reports')
                    ->where('report_type_id', $request->input('report_type_id'))
                    ->where('user_id', Auth::id())
                    ->where(function ($query) use ($message) {
                        return $query->whereExists(function ($query) use ($message) {
                            $query->select(DB::raw(1))
                                ->from('reportables')
                                ->whereColumn('reportables.report_id', 'reports.id')
                                ->where('reportable_id', $message->id)
                                ->where('reportable_type', Message::class);
                        });
                    }),
            ],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $message->reports()->create([
            'report_type_id' => $request->input('report_type_id'),
            'user_id' => Auth::id(),
            'description' => $request->input('description', null),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '留言檢舉成功',
        ]);
    }
}
