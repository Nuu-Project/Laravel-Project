<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Reportable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'report_id' => 'required|exists:reports,id',
            'description' => 'required|string|max:255',
            'product' => 'required|exists:products,id',
        ]);

        Reportable::create([
            'report_id' => $request->input('report_id'),
            'reportable_id' => $request->input('product'), // 關聯的 Product ID
            'reportable_type' => Product::class, // 關聯的模型類型
            'whistleblower_id' => Auth::id(),
            'description' => $request->input('description'),
        ]);

        return response()->json(['message' => '檢舉已成功提交']);
    }
}
