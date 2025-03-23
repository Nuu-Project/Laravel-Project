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
use Illuminate\View\View;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProductController extends Controller
{
    public function index(): View
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

        return view('user.products.index', ['userProducts' => $userProducts]);
    }

    public function create(): View
    {
        $tags = Tag::whereIn('type', Tagtype::cases())->get();

        return view('user.products.create', ['tags' => $tags]);
    }

    public function store(Request $request): RedirectResponse
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
            'encrypted_image_path' => ['required', 'array'],
            'encrypted_image_path.*' => ['required', 'string'],
        ];

        // 驗證
        $validated = $request->validate($rules, trans('product'));
        $mediaDisk = config('filesystems.media_disk', 'public_images');

        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'user_id' => auth()->id(),
        ]);

        if ($request->has('encrypted_image_path')) {
            // 取出所有加密的圖片路徑
            $encryptedPaths = $request->input('encrypted_image_path');

            // 遍歷所有的加密圖片
            foreach ($encryptedPaths as $encryptedPath) {
                // 解密圖片路徑
                $decryptedImagePath = decrypt($encryptedPath);

                $product->addMediaFromDisk($decryptedImagePath, 'temp')->toMediaCollection('images', $mediaDisk);

                // 刪除臨時圖片
                Storage::disk('local')->delete($decryptedImagePath);
            }
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

    public function edit(Request $request, Product $product): View
    {
        abort_unless($product->user_id == auth()->id(), 403, '您無權編輯此商品。');

        $productTags = $product->tags;
        $gradeTag = $productTags->firstWhere('type', Tagtype::Grade->value);
        $semesterTag = $productTags->firstWhere('type', Tagtype::Semester->value);
        $subjectTag = $productTags->firstWhere('type', Tagtype::Subject->value);
        $categoryTag = $productTags->firstWhere('type', Tagtype::Category->value);
        $tags = Tag::get();

        return view('user.products.edit', [
            'product' => $product, 'tags' => $tags, 'gradeTag' => $gradeTag,
            'semesterTag' => $semesterTag, 'categoryTag' => $categoryTag, 'subjectTag' => $subjectTag,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->user_id == auth()->id(), 403, '您無權編輯此商品。');

        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:50'],
            'grade' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Grade)],
            'semester' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Semester)],
            'subject' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Subject)],
            'category' => ['required', Rule::exists('tags', 'id')->where('type', Tagtype::Category)],
            'encrypted_image_path' => ['nullable', 'array'],
            'encrypted_image_path.*' => ['required', 'string'],
            'image_ids' => ['nullable', 'array', 'max:5'],
            'deleted_image_ids' => ['nullable', 'string'],
        ];

        $request->validate($rules, trans('product'));
        $mediaDisk = config('filesystems.media_disk', 'public_images');

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

        if ($request->has('encrypted_image_path')) {
            foreach ($request->input('encrypted_image_path') as $encryptedPath) {
                $decryptedImagePath = decrypt($encryptedPath);

                $product->addMediaFromDisk($decryptedImagePath, 'temp')->toMediaCollection('images', $mediaDisk);

                Storage::disk('temp')->delete($decryptedImagePath);
            }
        }

        // 過濾已刪除的 ID
        $imageIds = $request->input('image_ids', []);
        $validImageIds = array_diff($imageIds, $deletedImageIds);

        // 更新圖片的順序
        $product->getMedia('images')->each(function ($image) use ($validImageIds) {
            if (($index = array_search($image->id, $validImageIds)) !== false) {
                $image->order_column = $index + 1;
                $image->save();
            }
        });

        // 更新標籤
        $tagIds = [
            $request->input('grade'),
            $request->input('semester'),
            $request->input('subject'),
            $request->input('category'),
        ];
        $product->tags()->sync($tagIds);
        $product->save();

        return redirect()->route('user.products.index')->with('success', '商品更新成功！');
    }

    public function destroy(Product $product)
    {
        // 軟刪除產品，保留記錄但標記為已刪除
        $product->delete();

        // 重新導向到產品清單頁面，並標註成功訊息
        return redirect()->route('user.products.index')->with('success', '產品已成功刪除');
    }
    public function inactive(Product $product): RedirectResponse
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
