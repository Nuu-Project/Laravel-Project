<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ReportTypeController extends Controller
{
    public function index(): View
    {
        $reportTypes = QueryBuilder::for(ReportType::class)
            ->allowedFilters([
                AllowedFilter::callback('name', function (Builder $query, string $value) {
                    $query->where('name', 'like', "%{$value}%");
                }),
            ])
            ->withTrashed()
            ->paginate(10)
            ->withQueryString();

        return view('admin.report-types.index', [$reportTypes => 'reportTypes']);
    }

    public function create(): View
    {
        return view('admin.report-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('report_types', 'name->zh_TW'),
            ],
            'type' => ['required', 'string', 'max:255'],
            'order_column' => [
                'required', 'integer',
                Rule::unique('report_types')
                    ->where('type', $request->type),
            ],
        ]);

        ReportType::create($validatedData);

        return redirect()
            ->route('admin.report-types.index')
            ->with('message', '檢舉類型新增成功！');
    }

    public function edit(ReportType $reportType): View
    {
        return view('admin.report-types.edit', [$reportType => 'reportType']);
    }

    public function update(Request $request, ReportType $reportType): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('report_types', 'name->zh_TW')
                    ->ignore($reportType->id),
            ],
            'type' => ['required', 'string', 'max:255'],
            'order_column' => [
                'required', 'integer',
                Rule::unique('report_types')
                    ->where('type', $reportType->type)
                    ->ignore($reportType->id),
            ],
        ]);

        $reportType->update($validatedData);

        return redirect()
            ->route('admin.report-types.index')
            ->with('message', '檢舉類型更新成功！');
    }

    public function destroy(ReportType $reportType): RedirectResponse
    {
        $reportType->delete();

        return redirect()
            ->route('admin.report-types.index')
            ->with('success', '檢舉類型已刪除');
    }

    public function restore(ReportType $reportType): RedirectResponse
    {
        $reportType->restore();

        return redirect()
            ->route('admin.report-types.index')
            ->with('success', '檢舉類型已恢復');
    }
}
