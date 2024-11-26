<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class ManageableProductsController extends Controller
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
}
