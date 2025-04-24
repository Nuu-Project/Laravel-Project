<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\ReportType as ReportTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Rules\Report\ReportTypeRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReportController extends Controller
{
    public function store(Request $request, Product $product): JsonResponse
    {
        $validatedData = $request->validate([
            'report_type_id' => [
                'required',
                'exists:report_types,id',
                new ReportTypeRule(ReportTypeEnum::Product),
            ],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $report = $product->reports()->updateOrCreate(
            [
                'report_type_id' => $validatedData['report_type_id'],
                'user_id' => Auth::id(),
            ],
            [
                'description' => $validatedData['description'],
            ]
        );

        $status = $report->wasRecentlyCreated ? 'success' : 'updated';

        return response()->json(['status' => $status]);
    }
}
