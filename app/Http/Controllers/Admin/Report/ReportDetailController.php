<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Reportable;

class ReportDetailController extends Controller
{
    public function index()
    {
        // 查詢所有檢舉資料，並按 reportable_type 和 reportable_id 排序
        $reportables = Reportable::with('report')->orderBy('reportable_type')->orderBy('reportable_id')->get();

        return view('admin.report', compact('reportables'));
    }
}
