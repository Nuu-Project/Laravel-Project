<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\Tag;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $userId = Auth::user()->id;
    //     $products = Product::with(['media', 'user'])->get();
    //     if ($request->routeIs('products.index')) {
    //         return view('product', compact('products'));
    //     }elseif($request->routeIs('products.check')){  
    //         return view('product-check', compact('products'));
    //     }
    // }
    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $products = Product::with(['media', 'user'])->get();
        if ($request->routeIs('products.index')) {
            return view('Product', compact('products'));
        }elseif($request->routeIs('products.check')){  
            $userProducts = Product::with(['media', 'user'])
            ->where('user_id', $userId)
            ->get();
            return view('product-check', compact('userProducts'));
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        return view('Product-create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:50'],
            'price' => ['required','numeric','max:10'],
            'description' => ['nullable','string'],
        ]);

        $user = Auth::user(); 

        $product = new Product();
        $product->user()->associate($user);
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->save();

        // 添加圖片
        if ($request->hasFile('image')) {
            $product->addMedia($request->file('image'))->toMediaCollection('images');
        }

        // 添加標籤
        // $tagIds = $request->input('tags'); // 獲取選中的標籤 ID
        // $tags = Tag::find($tagIds);
        // $product->attachTags($tags);


        return redirect()->route('products.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
