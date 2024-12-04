<?php

namespace App\Http\Controllers\User;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\Tag;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $userProducts = Product::with(['media', 'user', 'tags'])
            ->where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->paginate(3);
        $message = $userProducts->isEmpty() ? '您目前沒有任何商品，趕緊刊登一個吧!' : null;

        return view('user.products.check', compact('userProducts', 'message'));
    }

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

    public function update(Request $request, Product $product)
    {
        // 基本驗證規則
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'grade' => ['required', 'string', 'not_in:選擇適用的年級...'],
            'semester' => ['required', 'string', 'not_in:選擇學期...'],
            'subject' => ['required', 'string', 'not_in:選擇科目...'],
            'category' => ['required', 'string', 'not_in:選擇課程類別...'],
            'images' => ['nullable', 'array', 'min:1', 'max:5'],
            'images.*' => [
                'nullable',
                'image',
                'mimes:svg,png,jpg,jpeg,gif',
                'max:2048',
                'dimensions:max_width=3200,max_height=3200'
            ],
            'image_ids' => ['nullable', 'array', 'max:5'],
            'deleted_image_ids' => ['nullable', 'string'],
        ];

        // 獲取要刪除的圖片 ID
        $deletedImageIds = json_decode($request->input('deleted_image_ids', '[]'), true);

        // 修改檢查圖片邏輯，更精確地判斷圖片狀態
        $existingImages = $product->getMedia('images');
        $remainingImages = $existingImages->whereNotIn('id', $deletedImageIds);

        // 檢查是否有新上傳的圖片
        $newImages = collect($request->file('images', []));
        $validNewImages = $newImages->filter()->isNotEmpty();

        // 計算最終的圖片數量（保留的現有圖片 + 新上傳的有效圖片）
        $totalImagesAfterUpdate = $remainingImages->count() + ($validNewImages ? $newImages->count() : 0);

        // 如果沒有任何圖片（包括現有的和新上傳的），則添加必填驗證
        if ($totalImagesAfterUpdate === 0) {
            $rules['images'] = ['required', 'array', 'min:1'];
            $messages['images.required'] = '請至少上傳一張商品圖片';
            $messages['images.min'] = '請至少上傳一張商品圖片';
        }

        // 驗證
        $request->validate($rules);

        // 更新產品資料
        $product->update($request->only(['name', 'price', 'description']));

        // 處理圖片
        if ($request->hasFile('images') || $request->has('image_ids')) {
            $existingMedia = $product->getMedia('images')->keyBy('id');
            $newOrder = [];

            // 處理新的圖片順序和刪除標記的圖片
            foreach ($request->input('image_ids', []) as $index => $imageId) {
                // 如果圖片被標記為刪除，跳過它
                if (in_array($imageId, $deletedImageIds)) {
                    continue;
                }

                if ($request->hasFile("images.$index")) {
                    // 新上傳的圖片
                    $newImage = $request->file("images.$index");
                    $media = $product->addMedia($newImage)
                        ->withCustomProperties(['order_column' => $index + 1])
                        ->toMediaCollection('images');
                    $newOrder[] = $media->id;
                } elseif ($imageId && $existingMedia->has($imageId)) {
                    // 保留的舊圖片
                    $existingMedia[$imageId]->order_column = $index + 1;
                    $existingMedia[$imageId]->save();
                    $newOrder[] = $imageId;
                }
            }

            // 刪除標記為刪除的圖片
            foreach ($deletedImageIds as $imageId) {
                if ($existingMedia->has($imageId)) {
                    $existingMedia[$imageId]->delete();
                }
            }

            // 重新排序剩餘的媒體
            $product->media()->whereIn('id', $newOrder)->each(function ($medium) use ($newOrder) {
                $medium->order_column = array_search($medium->id, $newOrder) + 1;
                $medium->save();
            });
        }

        // 處理標籤
        $product->tags()->detach(); // 先清除所有標籤

        // 獲取並附加新的標籤
        $tagTypes = [
            ['type' => '年級', 'slug' => $request->input('grade')],
            ['type' => '學期', 'slug' => $request->input('semester')],
            ['type' => '科目', 'slug' => $request->input('subject')],
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

        // 保存更新後的產品資料
        $product->save();

        // 重定向並返回成功消息
        return redirect()->route('user.products.index')->with('success', '商品更新成功！');
    }

    public function destroy(Product $product)
    {
        // 軟刪除產品，保留記錄但標記為已刪除
        $product->delete();

        // 重新導向到產品清單頁面，並標註成功訊息
        return redirect()->route('user.products.index')->with('success', '產品已成功刪除');
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

    public function demoteData(Product $product)
    {
        // 根據當前狀態切換到相反的狀態
        $newStatus = $product->status === ProductStatus::Active
            ? ProductStatus::Inactive
            : ProductStatus::Active;

        // 更新商品的狀態
        $product->update([
            'status' => $newStatus,
        ]);

        $message = "商品{$newStatus->label()}！";

        return redirect()->route('user.products.index')->with('success', $message);
    }



}
