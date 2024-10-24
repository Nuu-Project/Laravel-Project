<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class EditController extends Controller
{
    public function edit(Request $request, Product $product)
    {
        $gradeTag = $product->tags->firstWhere('type', '年級');
        $semesterTag = $product->tags->firstWhere('type', '學期');
        $categoryTag = $product->tags->firstWhere('type', '課程');
        $tags = Tag::whereNull('deleted_at')->get();

        if ($request->hasFile('images')) {
            $images = $request->file('images');

            // 先獲取並按 id 升序排序已存在的圖片
            $existingMedia = $product->getMedia('images')->sortBy('id')->values();

            foreach ($images as $index => $image) {
                // 確認是否需要替換該圖片
                if (isset($existingMedia[$index])) {
                    // 如果圖片已存在，則替換
                    $existingMedia[$index]->delete();
                }

                // 上傳新的圖片
                $product->addMedia($image)->toMediaCollection('images');
            }
        }

        return view('user.products.edit', compact('product', 'tags', 'gradeTag', 'semesterTag', 'categoryTag'));
    }
}
