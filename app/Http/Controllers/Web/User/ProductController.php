<?php

namespace App\Http\Controllers\Web\User;

use App\Enums\ProductStatus;
use App\Enums\Tagtype;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $userProducts = QueryBuilder::for(Product::class)
            ->where('user_id', $userId)
            ->allowedFilters([
                'name',
            ])
            ->with(['media', 'user', 'tags'])
            ->orderBy('updated_at', 'desc')
            ->paginate(3)
            ->withQueryString();

        return view('user.products.index', compact('userProducts'));
    }

    public function create()
    {
        $tags = Tag::whereIn('type', [Tagtype::Grade, Tagtype::Semester, Tagtype::Subject, Tagtype::Category])->get();

        return view('user.products.create', ['tags' => $tags]);
    }

    public function store(Request $request)
    {
        // 基本驗證規則
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999'],
            'description' => ['required', 'string', 'max:50'],
            'grade' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Grade)],
            'semester' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Semester)],
            'subject' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Subject)],
            'category' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Category)],
            'images' => ['required', 'array', 'min:1', 'max:5'],
        ];

        // 驗證
        $validated = $request->validate($rules, trans('product'));

        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'user_id' => auth()->id(),
        ]);

        // 處理圖片上傳
        if ($request->has('encrypted_image_path')) {
            // 解密圖片路徑
            $decryptedImagePath = decrypt($request->input('encrypted_image_path'));

            // 生成新的路徑
            $newImagePath = 'images/compressed_'.uniqid().'.jpg';
            // 移動圖片到新路徑
            Storage::move($decryptedImagePath, $newImagePath);

            // 將圖片添加到媒體庫
            $product->addMedia(Storage::path($newImagePath))->toMediaCollection('images');
        }

        // 獲取並附加新的標籤
        $tagIds = [
            $request->input('grade'),
            $request->input('semester'),
            $request->input('subject'),
            $request->input('category'),
        ];

        // 同步標籤到產品
        $product->tags()->sync($tagIds);

        return redirect()->route('user.products.create')->with('success', '產品已成功創建！');
    }

    public function edit(Request $request, Product $product)
    {
        abort_unless($product->user_id == auth()->id(), 403, '您無權編輯此商品。');

        $productTags = $product->tags;
        $gradeTag = $productTags->where('type', Tagtype::Grade)->first();
        $semesterTag = $productTags->where('type', Tagtype::Semester)->first();
        $subjectTag = $productTags->where('type', Tagtype::Subject)->first();
        $categoryTag = $productTags->where('type', Tagtype::Category)->first();
        $tags = Tag::get();

        return view('user.products.edit', compact('product', 'tags', 'gradeTag', 'semesterTag', 'categoryTag', 'subjectTag'));
    }

    public function update(Request $request, Product $product)
    {
        abort_unless($product->user_id == auth()->id(), 403, '您無權編輯此商品。');

        // 基本驗證規則
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:50'],
            'grade' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Grade)],
            'semester' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Semester)],
            'subject' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Subject)],
            'category' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Category)],
            'images' => ['nullable', 'array', 'min:1', 'max:5'],
            'image_ids' => ['nullable', 'array', 'max:5'],
            'deleted_image_ids' => ['nullable', 'string'],
        ];

        // 獲取要刪除的圖片 ID
        $deletedImageIds = json_decode($request->input('deleted_image_ids', '[]'), true);

        $existingImages = $product->getMedia('images');

        $remainingImages = $existingImages->whereNotIn('id', $deletedImageIds);

        // 檢查是否有新上傳的圖片
        $newImages = collect($request->file('images', []))->filter();

        // 計算最終的圖片數量
        $totalImagesAfterUpdate = $remainingImages->count() + $newImages->count();

        // 如果沒有任何圖片，添加必填驗證
        if ($totalImagesAfterUpdate === 0) {
            $rules['images'] = ['required', 'array', 'min:1'];
            $messages['images.required'] = '請至少上傳一張商品圖片';
            $messages['images.min'] = '請至少上傳一張商品圖片';
        }

        // 驗證
        $request->validate($rules, trans('product'));

        // 更新產品資料
        $product->update($request->only(['name', 'description']));

        // 刪除標記為刪除的圖片
        $existingImages->whereIn('id', $deletedImageIds)->each->delete();

        // 處理新上傳的圖片
        $newImages->each(function ($image, $index) use ($product) {
            try {
                // 解密圖片路徑（假設路徑是加密的）
                $decryptedImagePath = decrypt($image->getPathname());

                // 生成新的路徑
                $newImagePath = 'images/compressed_'.uniqid().'.jpg';

                // 將圖片從解密後的路徑移動或複製到新的路徑
                if (Storage::exists($decryptedImagePath)) {
                    // 複製圖片到新路徑
                    Storage::move($decryptedImagePath, $newImagePath);

                    // 添加壓縮後的圖片到媒體集合
                    $product->addMedia(Storage::path($newImagePath))
                        ->withCustomProperties(['order_column' => $index + 1])
                        ->toMediaCollection('images');
                } else {
                    \Log::error('解密後的圖片路徑無效：'.$decryptedImagePath);
                }
            } catch (\Exception $e) {
                \Log::error('圖片處理失敗：'.$e->getMessage());
            }
        });

        // 更新現有圖片的順序
        $imageIds = $request->input('image_ids', []);
        $existingImages->each(function ($image) use ($imageIds) {
            if (($index = array_search($image->id, $imageIds)) !== false) {
                $image->order_column = $index + 1;
                $image->save();
            }
        });

        // 獲取並附加新的標籤
        $tagIds = [
            $request->input('grade'),
            $request->input('semester'),
            $request->input('subject'),
            $request->input('category'),
        ];

        // 同步標籤到產品
        $product->tags()->sync($tagIds);

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

    public function inactive(Product $product)
    {
        // 根據當前狀態切換到相反的狀態
        $newStatus = $product->status === ProductStatus::Active
            ? ProductStatus::Inactive
            : ProductStatus::Active;

        // 更新商品的狀態
        $product->update([
            'status' => $newStatus,
        ]);

        $message = "商品{$newStatus->name()}！";

        return redirect()->route('user.products.index')->with('success', $message);
    }
}