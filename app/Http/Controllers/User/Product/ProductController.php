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
        // 驗證輸入資料
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'grade' => ['required', 'string'],
            'semester' => ['required', 'string'],
            'category' => ['required', 'string'],
            'images' => ['required', 'array', 'max:5'],
            'images.*' => ['nullable', 'image', 'max:2048'],
            'image_ids' => ['nullable', 'array', 'max:5'],
        ]);

        // 更新產品資料（只更新基本資料）
        $product->update($request->only([
            'name',
            'price',
            'description'
        ]));

        // 處理圖片上傳和更新
        if ($request->hasFile('images') || $request->has('image_ids')) {
            $existingMedia = $product->getMedia('images')->keyBy('id');
            $newOrder = [];

            foreach ($request->input('image_ids', []) as $index => $imageId) {
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

            // 刪除不在新順序中的舊圖片
            foreach ($existingMedia as $media) {
                if (! in_array($media->id, $newOrder)) {
                    $media->delete();
                }
            }

            // 重新排序媒體
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
        // 软删除产品，保留记录但标记为已删除
        $product->delete();

        // 重定向到产品列表页面，并带有成功消息
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
