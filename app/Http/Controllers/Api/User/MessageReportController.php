<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\ReportType as ReportTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Rules\Report\ReportTypeRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageReportController extends Controller
{
    public function store(Request $request, Message $message): JsonResponse
    {
        $validatedData = $request->validate([
            'report_type_id' => [
                'required',
                'exists:report_types,id',
                new ReportTypeRule(ReportTypeEnum::Message),
            ],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $report = $message->reports()->updateOrCreate(
            [
                'report_type_id' => $validatedData['report_type_id'],
                'user_id' => Auth::id(),
            ],
            [
                'description' => $validatedData['description'],
            ]
        );

        $status = $report->wasRecentlyCreated ? 'success' : 'updated';

        return response()->json([
            'status' => $status,
            'message' => $status === 'success' ? '留言檢舉成功' : '檢舉已更新',
        ]);
    }
}
