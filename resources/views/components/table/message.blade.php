@props(['messages'])

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">用戶名稱</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">商品</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">留言</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">留言日期</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">刪除</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
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
                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg">
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
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    @if ($message->product)
                        <form
                            action="{{ route('user.products.messages.destroy', ['product' => $message->product->id, 'message' => $message->id]) }}"
                            method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                onclick="return confirm('{{ __('確定要刪除這條評論嗎？') }}')">Delete</button>
                        </form>
                    @else
                        <span class="text-gray-400">No action</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
