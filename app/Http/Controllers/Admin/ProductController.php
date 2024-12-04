<?php

namespace App\Http\Controllers\Admin;

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
            ->get();

        // 返回到視圖，並傳遞商品資料
        return view('admin.products', compact('products'));
    }
}
