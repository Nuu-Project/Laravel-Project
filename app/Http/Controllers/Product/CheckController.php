<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\Tag;

class CheckController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $userProducts = Product::with(['media', 'user'])
            ->where('user_id', $userId)
            ->paginate(3);
        $message = $userProducts->isEmpty() ? '您目前沒有任何商品，趕緊刊登一個吧!' : null;

        return view('login.Product-check', compact('userProducts', 'message'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

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

    public function destroy(Product $product)
    {
        // 软删除产品，保留记录但标记为已删除
        $product->delete();

        // 重定向到产品列表页面，并带有成功消息
        return redirect()->route('products.index')->with('success', '產品已成功刪除');
    }
}
