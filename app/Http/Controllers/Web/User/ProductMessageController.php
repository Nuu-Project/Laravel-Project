<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Mail\CommentDeletedNotification;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ProductMessageController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->messages()->create($validated + [
            'product_id' => $product->id,
        ]);

        return redirect()->route('products.show', ['product' => $product]);
    }

    public function edit(Product $product, Message $message): View
    {
        abort_unless($message->user_id == auth()->id(), 403, '您無權編輯此留言。');

        Gate::authorize('update', $message);

        return view('messages.edit', [
            'message' => $message,
            'productId' => $product,
        ]);
    }

    public function update(Request $request, Product $product, Message $message): RedirectResponse
    {
        abort_unless($message->user_id == auth()->id(), 403, '您無權編輯此留言。');

        Gate::authorize('update', $message);
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);
        $message->update($validated);

        return redirect()->route('products.show', ['product' => $product]);
    }

    public function destroy(Product $product, Message $message): RedirectResponse
    {

        $this->authorize('delete', $message);

        // 取得留言創建者的資訊
        $recipientEmail = $message->user->email;
        $commentContent = $message->message; // 取得被刪除的留言內容
        $userName = $message->user->name;   // 取得留言創建者的名稱 (假設 User 模型有 name 屬性)

        // 建立 Mailable 實例
        $mailable = new CommentDeletedNotification($commentContent, null, $userName); // 這裡沒有提供刪除原因

        // 發送郵件
        Mail::to($recipientEmail)->send($mailable);

        // 或者使用隊列來異步發送郵件
        // Mail::to($recipientEmail)->queue($mailable);

        $message->delete();

        return redirect()->route('products.show', ['product' => $product])
            ->with('success', 'Review deleted successfully.');
    }

    public function reply(Request $request, Product $product, Message $message): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        // 建立回覆留言，並關聯到原始留言
        $request->user()->messages()->create($validated + [
            'product_id' => $product->id,
            'reply_to_id' => $message->id, // 設定回覆的留言 ID
        ]);

        return redirect()->route('products.show', ['product' => $product]);
    }
}
