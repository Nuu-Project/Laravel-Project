<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\ReportType as ReportTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\Report\UniqueReportRule;
use App\Rules\Report\ReportTypeRule;

class ProductReportController extends Controller
{
    public function store(Request $request, Product $product): JsonResponse
    {
        $validatedData = $request->validate([
            'report_type_id' => [
                'required',
                'exists:report_types,id',
                new ReportTypeRule(ReportTypeEnum::Product),
                new UniqueReportRule($product->id, Product::class),
            ],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $product->reports()->create($validatedData + [
            'user_id' => Auth::id(),
        ]);

        return response()->json(['status' => 'success']);
    }
}
