<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DownShelvesController extends Controller
{
    public function index(): View
    {
        // 抓取所有商品
        $products = Product::all();

        // 返回到視圖，並傳遞商品資料
        return view('admin.check', compact('products'));
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
