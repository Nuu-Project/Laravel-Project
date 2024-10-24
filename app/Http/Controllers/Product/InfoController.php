<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;

class InfoController extends Controller
{
    public function index(Product $product)
    {
        $chirps = $product->chirps()->with('user')->latest()->get();

        return view('user.products.info', compact('product', 'chirps'));
    }
}
