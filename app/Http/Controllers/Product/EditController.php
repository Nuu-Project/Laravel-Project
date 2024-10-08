<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\Tag;

class EditController extends Controller
{
    public function index() {}

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Request $request,Product $product)
    {
        $gradeTag = $product->tags->firstWhere('type', '年級');
        $semesterTag = $product->tags->firstWhere('type', '學期');
        $categoryTag = $product->tags->firstWhere('type', '課程');
        $tags = Tag::all();

        return view('login.Product-edit', compact('product', 'tags', 'gradeTag', 'semesterTag', 'categoryTag'));
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
