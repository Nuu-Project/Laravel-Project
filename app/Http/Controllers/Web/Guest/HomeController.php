<?php

namespace App\Http\Controllers\Web\Guest;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $products = Product::whereHas('tags', function ($query) {
            $query->where('type', '科目');
        })
            ->with(['media', 'user', 'tags'])
            ->get()
            ->groupBy(function ($product) {
                return $product->tags->where('type', '科目')->pluck('id');
            })
            ->sortByDesc(function ($group) {
                return $group->count();
            })
            ->first()
            ->sortByDesc('created_at')
            ->take(3);

        return view('Home', ['products' => $products]);
    }
}
