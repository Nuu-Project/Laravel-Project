<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductMessageController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, $productId): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->messages()->create([
            'message' => $validated['message'],
            'product_id' => $productId,
        ]);

        return redirect()->route('products.show', ['product' => $productId]);
    }

    public function edit($productId, Message $message): View
    {
        Gate::authorize('update', $message);

        return view('messages.edit', [
            'message' => $message,
            'productId' => $productId,
        ]);
    }

    public function update(Request $request, $productId, Message $message): RedirectResponse
    {
        Gate::authorize('update', $message);
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);
        $message->update($validated);

        return redirect()->route('products.show', ['product' => $productId]);
    }

    public function destroy($productId, Message $message)
    {
        $this->authorize('delete', $message);
        $message->delete();

        return redirect()->route('products.show', ['product' => $productId])
            ->with('success', 'Review deleted successfully.');
    }
}
