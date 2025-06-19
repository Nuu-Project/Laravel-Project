@php
    use App\Enums\ReportType;
@endphp

@props(['messages'])

<x-table.gray-200>
    <x-thead.gray-50>
        <tr>
            <x-td>用戶名稱</x-td>
            <x-td>商品</x-td>
            <x-td>留言</x-td>
            <x-td>留言日期</x-td>
            <x-td>操作</x-td>
            <x-td>刪除</x-td>
        </tr>
    </x-thead.gray-50>
    <x-tbody>
        @foreach ($messages as $message)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-full" src="{{ asset('images/account.png') }}"
                                alt="{{ $message->user->name }}">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $message->user->name }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $message->product->name ?? 'No associated product' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    <div class="message-container">
                        <span class="message-content">{{ $message->message }}</span>
                        @if (mb_strlen($message->message) > 15)
                            <button class="expand-btn ml-2 text-blue-500 hover:text-blue-700">
                                <svg class="w-4 h-4 inline-block transform transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $message->created_at->format('Y-m-d H:i:s') }}</td>

                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex items-center space-x-2">
                        @php
                            $isReply = $message->reply_to_id !== null;

                            if ($isReply) {
                                $parentMessage = \App\Models\Message::find($message->reply_to_id);
                                $parentId = $parentMessage ? $parentMessage->id : null;
                                $position = \Illuminate\Support\Facades\DB::table('messages')
                                    ->whereNull('reply_to_id')
                                    ->where('product_id', $message->product_id)
                                    ->where(function ($query) use ($parentMessage) {
                                        $query
                                            ->where('created_at', '<', $parentMessage->created_at)
                                            ->orWhere(function ($q) use ($parentMessage) {
                                                $q->where('created_at', '=', $parentMessage->created_at)->where(
                                                    'id',
                                                    '<=',
                                                    $parentMessage->id,
                                                );
                                            });
                                    })
                                    ->count();
                                $page = ceil(($position + 1) / 10);

                                // 更新URL，包含必要的參數以確保折疊的留言能被展開
                                $url =
                                    route('products.show', ['product' => $message->product_id]) .
                                    "?page={$page}&scrollCenter=true&highlight={$message->id}&forceExpand=1#message-{$parentId}";
                            } else {
                                $position = \Illuminate\Support\Facades\DB::table('messages')
                                    ->whereNull('reply_to_id')
                                    ->where('product_id', $message->product_id)
                                    ->where(function ($query) use ($message) {
                                        $query
                                            ->where('created_at', '<', $message->created_at)
                                            ->orWhere(function ($q) use ($message) {
                                                $q->where('created_at', '=', $message->created_at)->where(
                                                    'id',
                                                    '<=',
                                                    $message->id,
                                                );
                                            });
                                    })
                                    ->count();
                                $page = ceil(($position + 1) / 10);
                                $url =
                                    route('products.show', ['product' => $message->product_id]) .
                                    "?page={$page}&scrollCenter=true#message-{$message->id}";
                            }
                        @endphp
                        <a href="{{ $url }}">
                            <x-button.blue-short>
                                前往
                            </x-button.blue-short>
                        </a>

                        <a
                            href="{{ route('admin.reports.index', ['filter[reportable_id]' => $message->id, 'filter[type]' => ReportType::Message->value]) }}">
                            <x-button.red-short>
                                檢舉詳情
                            </x-button.red-short>
                        </a>
                    </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    @if ($message->product)
                        <form action="{{ route('admin.messages.destroy', ['message' => $message->id]) }}" method="POST"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium"
                                onclick="return confirm('{{ __('確定要刪除這條評論嗎？') }}')">刪除</button>
                        </form>
                    @else
                        <span class="text-gray-400">無法操作</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </x-gray-200>
</x-table.gray-200>
