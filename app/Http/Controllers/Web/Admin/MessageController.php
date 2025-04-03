<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Notifications\ProductMessageDeletedNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = QueryBuilder::for(Message::class)
            ->allowedFilters([
                AllowedFilter::callback('name', function (Builder $query, string $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('name', 'like', "%{$value}%");
                    });
                }),
            ])
            ->with(['user', 'product'])
            ->paginate(10)
            ->withQueryString();

        return view('admin.messages.index', ['messages' => $messages]);
    }

    public function destroy(Message $message)
    {
        abort_unless(Auth::check(), 403, '您無權編輯此留言。');

        $user = $message->user;

        if ($user) {

            $user->notify(new ProductMessageDeletedNotification($message));
        }

        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', '留言已刪除');
    }
}
