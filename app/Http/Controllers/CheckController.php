<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Report;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class checkController extends Controller
{
    public function index($productId): View
    {
        $product = Product::findOrFail($productId);
        
        $chirps = $product->chirps()->with('user')->get();
        $reports = Report::where('type', '商品')->get()->mapWithKeys(function ($item) {
            return [$item->id => json_decode($item->name, true)['zh']];
        });

        return view('user.products.info' , compact('chirps','product', 'reports'));
    }
    public function demoteData(Request $request, Product $product)
    {
        if ($product->status == 100) {
            $newStatus = 200;
            $message = '商品已下架！';  // 當前狀態是 100（上架），切換為 200（下架）
        } else {
            $newStatus = 100;
            $message = '商品已上架！';  // 當前狀態是 200（下架），切換為 100（上架）
        }

        // 更新商品的狀態
        $product->update([
            'status' => $newStatus,
        ]);

        // 返回更新成功的響應和相應的消息
        return response()->json([
            'message' => $message,
            'new_status' => $newStatus,
        ], 200);
    }
}
