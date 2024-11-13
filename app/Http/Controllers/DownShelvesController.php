<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Enums\ProductStatus;
class DownShelvesController extends Controller
{
    public function index(): View
    {
        // 抓取所有商品
        $products = Product::all();

        // 返回到視圖，並傳遞商品資料
        return view('admin.check', compact('products'));
    }

    public function demoteData(Product $product)
    {
        // 根據當前狀態切換到相反的狀態
        $newStatus = $product->status === ProductStatus::Active
            ? ProductStatus::Inactive
            : ProductStatus::Active;

        // 更新商品的狀態
        $product->update([
            'status' => $newStatus,
        ]);

        $message = "商品{$newStatus->label()}！";

        return redirect()->route('admin.products.index')->with('success', $message);
    }
}
