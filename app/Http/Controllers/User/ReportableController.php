<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Reportable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportableController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'report_id' => 'required|exists:reports,id',
            'description' => 'required|string|max:255',
        ]);

        $reportable = Reportable::firstOrCreate(
            [
                'report_id' => $request->input('report_id'),
                'reportable_id' => $product->id, // 關聯的 Product ID
                'reportable_type' => Product::class, // 關聯的模型類型
                'user_id' => Auth::id(),
            ],
            [
                'description' => $request->input('description'),
            ]
        );

        if (! $reportable->wasRecentlyCreated) {
            return response()->json([
                'message' => '你已檢舉過了',
                'description' => $reportable->description,
            ]);
        }

        return response()->json(['message' => '檢舉已成功提交']);
    }
}
