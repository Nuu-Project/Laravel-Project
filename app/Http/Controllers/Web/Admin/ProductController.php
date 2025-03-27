<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::callback('name', function (Builder $query, string $value) {
                    $query->where('name', 'like', "%{$value}%");
                }),
                AllowedFilter::callback('user', function (Builder $query, string $value) {
                    $query->whereHas('user', function (Builder $query) use ($value) {
                        $query->where('name', 'like', "%{$value}%");
                    });
                }),
            ])
            ->withCount('reports')
            ->with('user')
            ->paginate(10)
            ->withQueryString();

        // 返回到視圖，並傳遞商品資料
        return view('admin.products.index', ['products' => $products]);
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

        return redirect()->route('admin.products.index')->with('success', $message);
    }
}
