<?php

namespace App\Http\Controllers\User\Product;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\Tag;

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

    public function update(Request $request, Product $product)
    {
        // 基本驗證規則
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'grade' => ['required', 'string', 'not_in:選擇適用的年級...'],
            'semester' => ['required', 'string', 'not_in:選擇學期...'],
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

    public function deleteImage(Product $product, $imageId)
    {
        $media = $product->getMedia('images')->where('id', $imageId)->first();

        if ($media) {
            // 刪除媒體文件
            $media->delete();

            // 重新排序剩餘的媒體
            $remainingMedia = $product->getMedia('images')->sortBy('order_column')->values();
            foreach ($remainingMedia as $index => $medium) {
                $medium->order_column = $index + 1;
                $medium->save();
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => '找不到指定的圖片']);
    }
}
