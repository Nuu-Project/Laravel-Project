<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Reportable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductReportableController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'report_id' => [
                'required',
                'exists:reports,id',
                Rule::unique('reportables')
                    ->where('reportable_id', $product->id)
                    ->where('reportable_type', Product::class)
                    ->where('user_id', Auth::id()),
            ],
            'description' => 'required|string|max:255',
        ]);

        Reportable::create([
            'report_id' => $request->input('report_id'),
            'reportable_id' => $product->id, // 關聯的 Product ID
            'reportable_type' => Product::class, // 關聯的模型類型
            'user_id' => Auth::id(),
            'description' => $request->input('description'),
        ]);

        return response()->json(['status' => 'success']);
    }
}
