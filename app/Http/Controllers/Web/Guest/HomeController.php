<?php

namespace App\Http\Controllers\Web\Guest;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $topTags = Cache::remember('top_tags', 86400, function () {
            return Tag::withCount(['taggables as product_count' => function ($query) {
                $query->where('taggable_type', Product::class);
            }])
                ->where('type', '科目')
                ->orderByDesc('product_count')
                ->take(3)
                ->get();
        });

        $products = Cache::remember('top_tags_products', 3600, function () use ($topTags) {
            return $this->retrieveTopTagsProducts($topTags);
        });

        if ($products->contains(function ($product) {
            return $product->status !== ProductStatus::Active->value;
        })) {
            Cache::forget('top_tags_products');
            $products = $this->retrieveTopTagsProducts($topTags);
            Cache::put('top_tags_products', $products, 3600);
        }

        return view('Home', ['products' => $products]);
    }

    protected function retrieveTopTagsProducts(Collection $topTags): Collection
    {
        $products = collect();
        foreach ($topTags as $tag) {
            $tagProducts = Product::where('status', ProductStatus::Active->value)
                ->whereHas('tags', function ($query) use ($tag) {
                    $query->where('id', $tag->id);
                })
                ->with(['media', 'user', 'tags'])
                ->where('created_at', '>', Carbon::now()->subDays(7))
                ->orderByDesc('created_at')
                ->take(1)
                ->get();
            $products = $products->merge($tagProducts);
        }

        $neededCount = 3 - $products->count();
        if ($neededCount > 0) {
            $existingIds = $products->pluck('id')->toArray();

            $additionalProducts = Product::where('status', ProductStatus::Active->value)
                ->when(! empty($existingIds), function ($query) use ($existingIds) {
                    $query->whereNotIn('id', $existingIds);
                })
                ->with(['media', 'user', 'tags'])
                ->orderByDesc('created_at')
                ->take($neededCount)
                ->get();

            $products = $products->merge($additionalProducts)->unique('id');
        }

        return $products->values();
    }
}
