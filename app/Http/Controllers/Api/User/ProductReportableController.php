<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Report;
use App\Models\Reportable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductReportableController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'report_type_id' => [
                'required',
                'exists:report_types,id',
                Rule::unique('reports')
                    ->where('report_type_id', $request->input('report_type_id'))
                    ->where('user_id', Auth::id())
                    ->where(function ($query) use ($product) {
                        return $query->whereExists(function ($query) use ($product) {
                            $query->select(DB::raw(1))
                                ->from('reportables')
                                ->whereColumn('reportables.report_id', 'reports.id')
                                ->where('reportable_id', $product->id)
                                ->where('reportable_type', Product::class);
                        });
                    }),
            ],
            'description' => 'required|string|max:255',
        ]);

        $report = Report::create([
            'report_type_id' => $request->input('report_type_id'),
            'user_id' => Auth::id(),
            'description' => $request->input('description'),
        ]);

        Reportable::create([
            'report_id' => $report->id,
            'reportable_id' => $product->id,
            'reportable_type' => Product::class,
        ]);

        return response()->json(['status' => 'success']);
    }
}
