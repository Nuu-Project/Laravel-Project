<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MessageController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $messages = QueryBuilder::for(Message::class)
            ->allowedFilters([
                AllowedFilter::callback('name', function (Builder $query, $value) {
                    $query->whereHas('user', function ($query) use ($value) {
                        $query->where('name', 'like', "%{$value}%");
                    });
                }),
            ])
            ->with(['user', 'product'])
            ->paginate(10)
            ->withQueryString();

        return view('admin.message', compact('messages'));
    }
}
