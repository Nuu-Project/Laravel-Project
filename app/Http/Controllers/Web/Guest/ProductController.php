<?php

namespace App\Http\Controllers\Web\Guest;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ReportType;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index(Request $request): View
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

        $allTags = Tag::get();

        return view('guest.products.index', ['products' => $products, 'allTags' => $allTags]);
    }

    public function show(Product $product): View
    {
        if ($product->status !== ProductStatus::Active) {
            abort(404);
        }
        // 獲取商品留言
        $messages = $product->messages()
            ->withTrashed()
            ->whereNull('reply_to_id')
            ->with([
                'user',
                'replies' => function ($query) {
                    $query->withTrashed();
                },
                'replies.user',
            ])
            ->oldest()
            ->paginate(10);

        // 獲取商品檢舉類型
        $productReports = ReportType::where('type', '商品')->get()->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        });

        // 獲取留言檢舉類型
        $messageReports = ReportType::where('type', '留言')->get()->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        });

        // 將資料傳遞到視圖
        return view('guest.products.show', [
            'messages' => $messages, 'product' => $product,
            'productReports' => $productReports, 'messageReports' => $messageReports,
        ]);
    }
}
