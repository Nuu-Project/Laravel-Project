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
    if ($request->routeIs('products.index')) {
        $products = Product::with(['media', 'user'])
            ->where('status', 100)
            ->paginate(3);
        return view('n_login.Product', compact('products'));
    } elseif ($request->routeIs('products.check')) {  
        $userId = Auth::user()->id;
        $userProducts = Product::with(['media', 'user'])
            ->where('user_id', $userId)
            ->paginate(3);
        $message = $userProducts->isEmpty() ? '您目前沒有任何商品，趕緊刊登一個吧!' : null;
        return view('login.Product-check', compact('userProducts', 'message'));
    } elseif ($request->routeIs('products.info')) {
        $chirps = Auth::user()->chirps()->latest()->get(); // 獲取當前用戶的 chirps
        $products = Product::with(['media', 'user'])->get(); // 根據需求獲取相關產品
        return view('login.Product-info', compact('chirps', 'products'));
    }
    
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();

        return view('login.Product-create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'digits_between:1,10'],
            'description' => ['required', 'string'],
            'grade' => ['required', 'string'],      
            'semester' => ['required', 'string'],   
            'category' => ['required', 'string'],   
            'image' => ['nullable', 'image'],       
        ]);

        $product = Product::create($validated + [
            'user_id' => auth()->id(),
        ]);

        // 添加圖片
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $index => $image) {
                if ($index >= 5) break; // 最多處理 5 張圖片
                $product->addMedia($image)->toMediaCollection('images');
            }
        }

        // 獲取表單資料
        $gradeSlug = $request->input('grade');      
        $semesterSlug = $request->input('semester'); 
        $categorySlug = $request->input('category'); 

        // 根據年級查找對應的年級標籤
        $gradeTag = Tag::where('slug->zh', $gradeSlug)->where('type', '年級')->first();
        // 根據學期查找對應的學期標籤
        $semesterTag = Tag::where('slug->zh', $semesterSlug)->where('type', '學期')->first();
        // 根據課程類別查找對應的課程標籤
        $categoryTag = Tag::where('slug->zh', $categorySlug)->where('type', '課程')->first();

        // 附加年級標籤到產品
        if ($gradeTag) {
            $product->attachTag($gradeTag);
        }
        // 附加學期標籤到產品
        if ($semesterTag) {
            $product->attachTag($semesterTag);
        }
        // 附加課程標籤到產品
        if ($categoryTag) {
            $product->attachTag($categoryTag);
        }

        return redirect()->route('products.create')->with('success', '產品已成功創建並附加標籤');
    }

    /**
     * Display the specified resource.
     */
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,Product $product)
    {
        $gradeTag = $product->tags->firstWhere('type', '年級');
        $semesterTag = $product->tags->firstWhere('type', '學期');
        $categoryTag = $product->tags->firstWhere('type', '課程');
        $tags = Tag::all();
        return view('login.Product-edit', compact('product', 'tags', 'gradeTag', 'semesterTag', 'categoryTag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function demoteData(Request $request, Product $product)
    {
        if ($product->status == 100) {
            $newStatus = 200;
            $message = '商品已下架！';  // 當前狀態是 100（上架），切換為 200（下架）
        } else {
            $newStatus = 100;
            $message = '商品已上架！';  // 當前狀態是 200（下架），切換為 100（上架）
        }

        // 更新商品的狀態
        $product->update([
            'status' => $newStatus,         
        ]);

        // 返回更新成功的響應和相應的消息
        return response()->json([
            'message' => $message,
            'new_status' => $newStatus,
        ], 200);
    }

    public function update(Request $request, Product $product)
{
    // 移除不必要的查找，因為已經自動綁定了 $product

    // 驗證輸入資料
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:50'],
        'price' => ['required', 'numeric', 'digits_between:1,10'],
        'description' => ['required', 'string'],
        'grade' => ['required', 'string'],      
        'semester' => ['required', 'string'],   
        'category' => ['required', 'string'],   
        'image' => ['nullable', 'image'],       
    ]);

    // 更新產品資料
    $product->update($validated);

    // 如果有上傳圖片，處理圖片
    if ($request->hasFile('image')) {
        // 刪除舊圖片（如果有）
        if ($product->hasMedia('images')) {
            $product->clearMediaCollection('images');
        }
        // 添加新圖片
        $product->addMedia($request->file('image'))->toMediaCollection('images');
    }

    // 獲取表單資料中的標籤
    $gradeSlug = $request->input('grade');      
    $semesterSlug = $request->input('semester'); 
    $categorySlug = $request->input('category'); 

    // 根據年級查找對應的年級標籤
    $gradeTag = Tag::where('slug->zh', $gradeSlug)->where('type', '年級')->first();
    // 根據學期查找對應的學期標籤
    $semesterTag = Tag::where('slug->zh', $semesterSlug)->where('type', '學期')->first();
    // 根據課程類別查找對應的課程標籤
    $categoryTag = Tag::where('slug->zh', $categorySlug)->where('type', '課程')->first();

    // 先清除所有的標籤
    $product->tags()->detach();

    // 附加年級標籤到產品
    if ($gradeTag) {
        $product->attachTag($gradeTag);
    }
    // 附加學期標籤到產品
    if ($semesterTag) {
        $product->attachTag($semesterTag);
    }
    // 附加課程標籤到產品
    if ($categoryTag) {
        $product->attachTag($categoryTag);
    }

    // 保存更新後的產品資料
    $product->save();

    // 重定向並返回成功消息
    return redirect()->route('products.check')->with('success', '商品更新成功！');
}
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // 清空產品的媒體集合
        $product->clearMediaCollection('images');

        // 刪除產品本身，這將自動處理標籤的分離
        $product->delete();

        // 重定向到產品列表頁面，並帶有成功消息
        return redirect()->route('products.index')->with('success', '產品及其關聯媒體和標籤已成功刪除');
    }
}
