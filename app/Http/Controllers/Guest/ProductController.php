<?php

namespace App\Http\Controllers\Guest;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\Tags\Tag;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $tagIds = array_filter($request->input('filter.tags', [])); // 過濾掉 null 值

        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([
                'name',
                AllowedFilter::callback('tags', function ($query) use ($tagIds) {
                    if (! empty($tagIds)) {
                        $query->whereHas('tags', function ($query) use ($tagIds) {
                            $query->whereIn('tags.id', $tagIds);
                        }, '=', count($tagIds));
                    }
                }),
            ])
            ->with(['media', 'user', 'tags'])
            ->where('status', ProductStatus::Active->value)
            ->paginate(6)
            ->withQueryString();

        $allTags = Tag::whereNull('deleted_at')->get();

        return view('guest.Product', compact('products', 'allTags'));
    }

    public function show($productId): View
    {
        $product = Product::with('tags')->findOrFail($productId);

        $chirps = $product->chirps()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        $reports = Report::where('type', '商品')->get()->mapWithKeys(function ($item) {
            return [$item->id => json_decode($item->name, true)['zh_TW']];
        });

        return view('user.products.info', compact('chirps', 'product', 'reports'));
    }
}
