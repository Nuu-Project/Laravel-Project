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

        // 返回到視圖，並傳遞商品資料
        return view('admin.check', compact('products'));
    }
}
