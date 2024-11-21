<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Reportable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReportDetailController extends Controller
{
    public function index()
    {
        // // 查詢所有檢舉資料，並按 reportable_type 和 reportable_id 排序
        // $reportables = Reportable::with('report')->orderBy('reportable_type')->orderBy('reportable_id')->paginate(5);

        $reportables = QueryBuilder::for(Reportable::class)
            ->allowedFilters([
                AllowedFilter::exact('reportable_type'),
                AllowedFilter::exact('reportable_id'),
            ])
            ->with('report')
            ->paginate(5);

        return view('admin.report', compact('reportables'));
    }
}
