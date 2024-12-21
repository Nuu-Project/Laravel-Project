<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->withCount('reportables')
            ->with('user')
            ->paginate(10);

        // 返回到視圖，並傳遞商品資料
        return view('admin.products', compact('products'));
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

        $message = "商品{$newStatus->name()}！";

        return redirect()->route('admin.products.index')->with('success', $message);
    }
}
