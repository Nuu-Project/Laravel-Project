<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class ManageableProductsController extends Controller
{
    public function index(): View
    {
        // 抓取所有商品
        $products = Product::all();

        $products = Product::withCount(['reportables' => function ($query) {
            $query->where('reportable_type', 'App\\Models\\Product');
        }])->get();

        // 返回到視圖，並傳遞商品資料
        return view('admin.check', compact('products'));
    }
}
