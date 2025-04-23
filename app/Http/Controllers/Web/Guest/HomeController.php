<?php

namespace App\Http\Controllers\Web\Guest;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $topTags = DB::table('tags')
            ->select('tags.id', 'tags.name', DB::raw('COUNT(taggables.tag_id) as tag_count'))
            ->join('taggables', 'tags.id', '=', 'taggables.tag_id')
            ->where('tags.type', '科目')
            ->whereNull('tags.deleted_at')
            ->where('taggables.taggable_type', Product::class)
            ->groupBy('tags.id')
            ->orderByDesc('tag_count')
            ->take(3)
            ->get();

        $products = collect();
        foreach ($topTags as $tag) {
            $tagProducts = Product::whereHas('tags', function ($query) use ($tag) {
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
            $additionalProducts = Product::with(['media', 'user', 'tags'])
                ->orderByDesc('created_at')
                ->take($neededCount)
                ->get();

            $products = $products->merge($additionalProducts);
        }

        return view('Home', ['products' => $products]);
    }
}
