@props(['messages'])

<x-table.gray-200>
    <x-thead.gray-50>
        <tr>
            <x-gray-900>用戶名稱</x-gray-900>
            <x-gray-900>商品</x-gray-900>
            <x-gray-900>留言</x-gray-900>
            <x-gray-900>留言日期</x-gray-900>
            <x-gray-900>操作</x-gray-900>
            <x-gray-900>刪除</x-gray-900>
        </tr>
    </x-thead.gray-50>
    <x-gray-200>
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
                        <a href="{{ route('products.show', ['product' => $message->product_id]) }}">
                            <x-button.blue-short>
                                前往
                            </x-button.blue-short>
                        </a>

                        <a
                            href="{{ route('admin.reports.index', ['filter[reportable_id]' => $message->id, 'filter[type]' => '留言']) }}">
                            <x-button.red-short>
                                檢舉詳情
                            </x-button.red-short>
                        </a>
                    </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    @if ($message->product)
                        <form
                            action="{{ route('user.products.messages.destroy', ['product' => $message->product->id, 'message' => $message->id]) }}"
                            method="POST" class="inline">
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
