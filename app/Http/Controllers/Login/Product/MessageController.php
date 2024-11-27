<?php

namespace App\Http\Controllers\Login\Product;

use App\Http\Controllers\Controller;
use App\Models\Chirp;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MessageController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, $productId): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->chirps()->create([
            'message' => $validated['message'],
            'product_id' => $productId,
        ]);

        return redirect()->route('products.show', ['product' => $productId]);
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

        return redirect()->route('products.show', ['product' => $productId]);
    }

    public function destroy($productId, Chirp $chirp)
    {
        $this->authorize('delete', $chirp);
        $chirp->delete();

        if (request()->is('admin/search*')) {
            return redirect()->route('admin.search')->with('success', 'Review deleted successfully.');
        }

        return redirect()->route('products.show', ['product' => $productId])
            ->with('success', 'Review deleted successfully.');
    }
}
