<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = QueryBuilder::for(Tag::class)
            ->allowedFilters([
                AllowedFilter::callback('name', function (Builder $query, string $value) {
                    $query->where('name', 'like', "%{$value}%");
                }),
            ])
            ->withTrashed()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tags.index', ['tags' => $tags]);
    }

    public function create(): View
    {
        return view('admin.tags.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('tags', 'name->zh_TW'),
            ],
            'slug' => [
                'required', 'string', 'max:255', 'regex:/^[a-z]+$/',
                Rule::unique('tags', 'slug->zh_TW'),
            ],
            'type' => ['required', 'string', 'max:255'],
            'order_column' => [
                'required', 'integer',
                Rule::unique('tags')
                    ->where('type', $request->type),
            ],
        ]);

        Tag::create($validatedData);

        return redirect()
            ->route('admin.tags.index')
            ->with('message', '標籤新增成功！');
    }

    public function edit(Tag $tag): View
    {
        return view('admin.tags.edit', ['tag' => $tag]);
    }

    public function update(Request $request, Tag $tag): RedirectResponse // 排除自己
    {
        $validatedData = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('tags', 'name->zh_TW')
                    ->ignore($tag->id),
            ],
            'slug' => [
                'required', 'string', 'max:255', 'regex:/^[a-z]+$/',
                Rule::unique('tags', 'slug->zh_TW')
                    ->ignore($tag->id),
            ],
            'type' => ['required', 'string', 'max:255'],
            'order_column' => [
                'required', 'integer',
                Rule::unique('tags')
                    ->where('type', $tag->type)
                    ->ignore($tag->id),
            ],
        ]);

        $tag->update($validatedData);

        return redirect()
            ->route('admin.tags.index')
            ->with('message', '標籤更新成功！');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', '標籤已刪除');
    }

    public function restore(Tag $tag): RedirectResponse
    {
        $tag->restore();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', '標籤已恢復');
    }
}
