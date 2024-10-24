<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\Product;
use App\Models\Report;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ChirpController extends Controller
{
    use AuthorizesRequests;

    public function index($productId): View
    {
        $product = Product::findOrFail($productId);

        $chirps = $product->chirps()->with('user')->get();
        $reports = Report::where('type', '商品')->get()->mapWithKeys(function ($item) {
            return [$item->id => json_decode($item->name, true)['zh']];
        });

        return view('user.products.info', compact('chirps', 'product', 'reports'));
    }

    public function adminSearch(Request $request): View
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

        return view('admin.search', compact('chirps'));
    }

    public function store(Request $request, $productId): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->chirps()->create([
            'message' => $validated['message'],
            'product_id' => $productId,
        ]);

        return redirect()->route('products.info', ['product' => $productId]);
    }

    public function edit($productId, Chirp $chirp): View
    {
        Gate::authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp' => $chirp,
            'productId' => $productId,
        ]);
    }

    public function update(Request $request, $productId, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp);
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);
        $chirp->update($validated);

        return redirect()->route('products.info', ['product' => $productId]);
    }

    public function destroy($productId, Chirp $chirp)
    {
        $this->authorize('delete', $chirp);
        $chirp->delete();

        if (request()->is('admin/search*')) {
            return redirect()->route('admin.search')->with('success', 'Review deleted successfully.');
        }

        return redirect()->route('products.info', ['product' => $productId])
            ->with('success', 'Review deleted successfully.');
    }
}
