<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\ReportType;
use App\Http\Controllers\Controller;
use App\Models\Reportable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReportController extends Controller
{
    public function index(): View
    {
        $reportables = QueryBuilder::for(Reportable::class)
            ->allowedFilters([
                'reportable_id',
                AllowedFilter::callback('type', function (Builder $query, string $type) {
                    if ($type === ReportType::Product->value()) {
                        $query->whereHas('report.reportType', function ($query) {
                            $query->where('type', ReportType::Product->value());
                        });
                    } elseif ($type === ReportType::Message->value()) {
                        $query->whereHas('report.reportType', function ($query) {
                            $query->where('type', ReportType::Message->value());
                        });
                    }
                }),
                AllowedFilter::callback('name', function (Builder $query, string $value) {
                    $type = request('filter.type');
                    if ($type === ReportType::Product->value()) {
                        $query->whereHasMorph('reportable', ['App\Models\Product'], function ($query) use ($value) {
                            $query->where('name', 'like', "%{$value}%");
                        });
                    } elseif ($type === ReportType::Message->value()) {
                        $query->whereHasMorph('reportable', ['App\Models\Message'], function ($query) use ($value) {
                            $query->where('message', 'like', "%{$value}%");
                        });
                    }
                }),
            ])
            ->with(['reportable', 'report', 'report.user', 'report.reportType'])
            ->paginate(10)
            ->withQueryString();

        return view('admin.reports.index', ['reportables' => $reportables]);
    }
}
