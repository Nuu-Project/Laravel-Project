<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reportable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReportDetailController extends Controller
{
    public function index()
    {

        $reportables = QueryBuilder::for(Reportable::class)
            ->allowedFilters([
                AllowedFilter::exact('reportable_id'),
            ])
            ->with('report')
            ->paginate(5);

        return view('admin.report', compact('reportables'));
    }
}
