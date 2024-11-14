<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class CreateController extends Controller
{
    public function create()
    {
        $tags = Tag::whereNull('deleted_at')->get(); // 修改這行

        return view('user.products.create', compact('tags'));
    }

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
                if ($index >= 5) {
                    break;
                } // 最多處理 5 張圖片
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

        return redirect()->route('user.products.create')->with('success', '產品已成功創建並附加標籤');
    }
}
