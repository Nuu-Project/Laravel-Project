<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\ReportType as ReportTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Rules\Report\ReportTypeRule;
use App\Rules\Report\UniqueReportRule;
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
                new UniqueReportRule($message->id, Message::class),
            ],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $message->reports()->create($validatedData + [
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '留言檢舉成功',
        ]);
    }
}
