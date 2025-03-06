<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\ReportType;

class MessageReportController extends Controller
{
    public function store(Request $request, Message $message)
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
            'description' => 'required|string|max:255',
        ], [
            'report_type_id.unique' => '您已經針對此留言回報過此類型的問題。'
        ]);

        $message->reports()->create([
            'report_type_id' => $request->input('report_type_id'),
            'user_id' => Auth::id(),
            'description' => $request->input('description', null),
            // 'status' => 'pending'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '留言檢舉成功'
        ]);
    }
}

