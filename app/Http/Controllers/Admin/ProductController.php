<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use App\Enums\ProductStatus;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->withCount('reportables')
            ->with('user')
            ->get();

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
