<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\Tag;
use App\Enums\ProductStatus;

class CheckController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $userProducts = Product::with(['media', 'user'])
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
            'price' => ['required', 'numeric', 'digits_between:1,10'],
            'description' => ['required', 'string'],
            'grade' => ['required', 'string'],
            'semester' => ['required', 'string'],
            'category' => ['required', 'string'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['nullable', 'image', 'max:2048'],
            'image_ids' => ['nullable', 'array', 'max:5'],
        ]);

        // 更新產品資料（排除 images 欄位）
        $product->update($request->except(['images', 'image_ids']));

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
