<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chirp;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChirpController extends Controller
{
    use AuthorizesRequests;

    public function adminMessage(Request $request): View
    {
        $query = Chirp::with(['user', 'product'])
            ->select('chirps.*')
            ->leftJoin('products', 'chirps.product_id', '=', 'products.id')
            ->latest('chirps.created_at');

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('chirps.message', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('product', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $chirps = $query->paginate(10);

        return view('admin.message', compact('chirps'));
    }
}
