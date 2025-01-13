<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return view('admin.tags.index', ['tags' => Tag::withTrashed()->paginate(10)]);
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateTag($request);
        Tag::create($validatedData);

        return redirect()
            ->route('admin.tags.index')
            ->with('message', '標籤新增成功！');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validatedData = $this->validateTag($request);
        $tag->update($validatedData);

        return redirect()
            ->route('admin.tags.index')
            ->with('message', '標籤更新成功！');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', '標籤已刪除');
    }

    public function restore(Tag $tag)
    {
        $tag->restore();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', '標籤已恢復');
    }

    private function validateTag(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'order_column' => 'required|integer',
        ]);
    }
}
