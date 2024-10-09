<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\Tag;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['media', 'user'])
            ->where('status', 100)
            ->paginate(3);
        
        $tags = Tag::all();

        return view('n_login.Product', compact('products','tags'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Product $product)
    {
        $tag = request('tag');

        // 根據標籤過濾商品
        $products = $product->whereHas('tags', function ($query) use ($tag) {
            $query->where('name', $tag);
        })->get();

        // 返回過濾後的商品
        return view('products.show', compact('products'));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
