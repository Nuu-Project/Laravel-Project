<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 獲取請求中的標籤 slug
        $tagSlugs = $request->input('tags', []);
        // 獲取搜索關鍵字
        $search = $request->input('search');

        // 修改：只獲取未被軟刪除的標籤
        $tags = Tag::whereIn('slug->zh', $tagSlugs)
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($tag) {
                return [
                    'name' => $tag->getTranslation('name', 'zh'),
                    'type' => $tag->type,
                ];
            })->toArray();

        // 根據所有標籤篩選商品
        $productsQuery = Product::with(['media', 'user'])
            ->where('status', 100);

        if (! empty($tags)) {
            foreach ($tags as $tag) {
                $productsQuery->whereHas('tags', function ($query) use ($tag) {
                    $query->where('name->zh', $tag['name'])
                        ->where('type', $tag['type'])
                        ->whereNull('deleted_at'); // 確保只使用未被軟刪除的標籤
                });
            }
        }

        // 如果有搜索關鍵字，則加入搜索條件
        if ($search) {
            $productsQuery->where('name', 'like', '%'.$search.'%');
        }

        $products = $productsQuery->paginate(6);

        // 修改：只獲取未被軟刪除的所有標籤
        $allTags = Tag::whereNull('deleted_at')->get();

        return view('guest.Product', compact('products', 'allTags', 'tagSlugs', 'search'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
