<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;

class DownShelvesController extends Controller
{
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
