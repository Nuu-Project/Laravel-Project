<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\ReportType as ReportTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MessageReportController extends Controller
{
    public function store(Request $request, Message $message): JsonResponse
    {
        $validatedData = $request->validate([
            'report_type_id' => [
                'required',
                'exists:report_types,id',
                function ($attribute, $value, $fail) {
                    $type = DB::table('report_types')->where('id', $value)->value('type');
                    if ($type !== ReportTypeEnum::Message->value) {
                        $fail('檢舉類型錯誤。');
                    }
                },
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

        $message->reports()->create($validatedData + [
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '留言檢舉成功',
        ]);
    }
}
