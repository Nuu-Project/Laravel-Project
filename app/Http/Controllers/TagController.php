<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::withTrashed()->get();
        
        return view('tags.index', compact('tags'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // 驗證請求資料
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'order_column' => 'required|integer',
    ]);
    
    // 新增標籤
    Tag::create([
        'name' => $validatedData['name'],
        'slug' => $validatedData['slug'],
        'type' => $validatedData['type'],
        'order_column' => $validatedData['order_column'],
    ]);

    $tags = Tag::all();
    
    // 返回成功消息
    // return response()->json(['message' => '標籤新增成功！', 'tag' => $tag], 201);
    return view('tags.index', ['tags' => $tags, 'message' => '標籤新增成功！']);
}

    /**
     * Display the specified resource.
     */
    public function show(tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // 使用 withTrashed() 查找包含已軟刪除的標籤
        $tag = Tag::withTrashed()->find($id);

        // 如果找不到標籤，返回錯誤消息
        if (!$tag) {
            return redirect()->route('tags.index')->with('error', '標籤未找到');
        }

        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'order_column' => 'required|integer',
        ]);
        
        $tag = Tag::withTrashed()->findOrFail($id);
        $tag->update($validatedData);
    
        return redirect()->route('tags.index')->with('message', '標籤更新成功！');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return redirect()->route('tags.index')->with('success', '標籤已刪除');
    }

    public function restore($id)
{
    // 使用 withTrashed() 查找包括已刪除的標籤
    $tag = Tag::withTrashed()->findOrFail($id);

    // 使用 restore() 恢復標籤
    $tag->restore();

    return redirect()->route('tags.index')->with('success', '標籤已恢復');
}

}