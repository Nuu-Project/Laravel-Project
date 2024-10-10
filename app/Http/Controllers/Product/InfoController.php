<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\Tag;

class InfoController extends Controller
{
    public function index()
    {
        $chirps = Auth::user()->chirps()->latest()->get(); // 獲取當前用戶的 chirps
        $products = Product::with(['media', 'user'])->get(); // 根據需求獲取相關產品
        return view('user.products.info', compact('chirps', 'products'));
    }

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
