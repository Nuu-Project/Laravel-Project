<?php

namespace App\Rules\Report;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UniqueReportRule implements ValidationRule
{
    private $reportableId;
    private $reportableType;

    public function __construct($reportableId, $reportableType)
    {
        $this->reportableId = $reportableId;
        $this->reportableType = $reportableType;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table('reports')
            ->where('report_type_id', $value)
            ->where('user_id', Auth::id())
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('reportables')
                    ->whereColumn('reportables.report_id', 'reports.id')
                    ->where('reportable_id', $this->reportableId)
                    ->where('reportable_type', $this->reportableType);
            })
            ->exists();

        if ($exists) {
            $fail('該檢舉已存在。');
        }
    }
}
