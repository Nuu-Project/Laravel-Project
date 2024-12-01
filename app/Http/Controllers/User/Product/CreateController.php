<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;
use Illuminate\Support\Facades\Storage;

class CreateController extends Controller
{
    public function create()
    {
        $tags = Tag::whereNull('deleted_at')->get();
        return view('user.products.create', compact('tags'));
    }

    public function store(Request $request)
    {
        // 基本驗證規則
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0','max:9999'],
            'description' => ['required', 'string'],
            'grade' => ['required', 'string', 'not_in:選擇適用的年級...'],
            'semester' => ['required', 'string', 'not_in:選擇學期...'],
            'category' => ['required', 'string', 'not_in:選擇課程類別...'],
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => [
                'required',
                'image',
                'mimes:svg,png,jpg,jpeg,gif',
                'max:2048',
                'dimensions:max_width=3200,max_height=3200'
            ],
        ];

        try {
            // 驗證
            $validated = $request->validate($rules);

            $product = Product::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'description' => $validated['description'],
                'user_id' => auth()->id(),
            ]);

            // 處理圖片上傳
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    if ($index >= 5) break;
                    $product->addMedia($image)->toMediaCollection('images');
                }
            }

            // 處理標籤
            $tagTypes = [
                ['type' => '年級', 'slug' => $request->input('grade')],
                ['type' => '學期', 'slug' => $request->input('semester')],
                ['type' => '課程', 'slug' => $request->input('category')]
            ];

            foreach ($tagTypes as $tagType) {
                $tag = Tag::where('slug->zh', $tagType['slug'])
                         ->where('type', $tagType['type'])
                         ->first();

                if ($tag) {
                    $product->attachTag($tag);
                }
            }

            return redirect()->route('user.products.create')->with('success', '產品已成功創建！');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function uploadTempImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'position' => 'required|integer|min:0|max:4'
        ]);

        try {
            $file = $request->file('image');
            $path = $file->store('temp/products', 'public');

            return response()->json([
                'success' => true,
                'path' => $path,
                'position' => $request->position
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '上傳圖片時發生錯誤'
            ]);
        }
    }
}
