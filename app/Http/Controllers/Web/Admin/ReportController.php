<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reportable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReportController extends Controller
{
    public function index()
    {
        $reportables = QueryBuilder::for(Reportable::class)
            ->allowedFilters([
                AllowedFilter::callback('reportable_id', function (Builder $query, int $value) {
                    $query->Where('reportable_id', $value);
                }),
                AllowedFilter::callback('name', function (Builder $query, string $value) {
                    $query->WhereHasMorph('reportable', ['App\Models\Message', 'App\Models\Product'], function ($query) use ($value) {
                        $query->where('name', 'like', "%{$value}%");
                    });
                }),
            ])
            ->with(['reportable', 'report', 'report.user', 'report.reportType'])
            ->paginate(10)
            ->withQueryString();

        return view('admin.reportables.index', compact('reportables'));
    }
}
