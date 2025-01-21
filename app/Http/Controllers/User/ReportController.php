<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Report;
use App\Models\Reportable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'report_id' => ['required', Rule::uniqid(Reportable::class)
                ->where('report_id', $product->id)
                ->where('user_id', auth()->id()),
            ],
            'description' => 'required|string|max:255',
            'product' => 'required|exists:products,id',
        ]);

        $reportable = Reportable::updateOrCreate(
            [
                'report_id' => $request->input('report_id'),
                'reportable_id' => $request->input('product'), // 關聯的 Product ID
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
