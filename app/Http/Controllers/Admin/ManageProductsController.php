<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ManageProductsController extends Controller
{
    public function index()
    {
        $userProducts = Product::with(['media', 'user']);
        $message = $userProducts->isEmpty() ? '您的網站目前沒有任何商品!' : null;

        return view('admin.product.', compact('userProducts', 'message'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show()
    {
        //
    }

    public function edit()
    {
        //
    }

    public function demoteData(Request $request, Product $product)
    {
        //
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
