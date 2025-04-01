<?php

namespace App\Rules\Report;

use App\Enums\ReportType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ReportTypeRule implements ValidationRule
{
    private ReportType $expectedType;

    public function __construct(ReportType $expectedType)
    {
        $this->expectedType = $expectedType;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $type = DB::table('report_types')->where('id', $value)->value('type');

        // 檢查是否符合預期的枚舉類型
        if ($type !== $this->expectedType->value()) {
            $fail('檢舉類型錯誤。');
        }
    }
}
