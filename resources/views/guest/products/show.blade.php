<x-template-layout>
    <script src="{{ asset('js/user/products/info.js') }}"></script>

    <div class="grid md:grid-cols-2 gap-6 lg:gap-12 items-start max-w-6xl px-4 mx-auto py-6">
        <div>
            @if ($product->media->isNotEmpty())
                <div class="relative mb-4">
                    @foreach ($product->getMedia('images') as $index => $media)
                        <img src="{{ $media->getUrl() }}" alt="Product Image {{ $index + 1 }}" width="1200"
                            height="900" style="aspect-ratio: 900 / 1200; object-fit: cover;"
                            class="w-full rounded-md object-cover {{ $index === 0 ? '' : 'hidden' }}"
                            data-index="{{ $index }}" />
                    @endforeach

                    <x-button.arrow-r id="leftArrow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </x-button.arrow-r>
                    <x-button.arrow-l id="rightArrow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </x-button.arrow-l>
                </div>
            @else
                <div>沒有圖片</div>
            @endif

            <div class="flex justify-start mt-4 space-x-4">
                @foreach ($product->getMedia('images') as $index => $media)
                    <div class="w-24 h-24 border-2 border-gray-300 flex items-center justify-center cursor-pointer thumbnail"
                        data-index="{{ $index }}">
                        <img src="{{ $media->getUrl() }}" alt="Thumbnail {{ $index + 1 }}"
                            class="max-w-full max-h-full object-cover" />
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-4 md:gap-10 items-start">
            <div class="flex items-start justify-between">
                <div class="flex-grow pr-4">
                    <h1 class="text-3xl font-bold break-words">商品名稱:{{ $product->name }}</h1>
                    <div class="mt-2">
                        <h2 class="font-semibold text-xl">用戶名稱:{{ $product->user->name }}</h2>
                    </div>
                </div>
                <button id="reportButton"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded flex-shrink-0"
                    data-reports='@json($productReports)'
                    data-store-url="{{ route('api.products.reports.store', ['product' => $product->id]) }}"
                    data-product-id="{{ $product->id }}">
                    檢舉
                </button>
            </div>
            <p class="text-2xl font-bold">${{ $product->price }}</p>
            <p>上架時間: {{ $product->created_at->format('Y-m-d H:i:s') }}</p>
            <p class="text-muted-foreground text-2xl">商品介紹: {{ $product->description }}</p>
        </div>
    </div>


    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('user.products.messages.store', ['product' => $product->id]) }}">
            @csrf
            <textarea name="message" placeholder="{{ __('想問什麼?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->store->get('message')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('留言') }}</x-primary-button>
        </form>

        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            @foreach ($messages as $message)
                <div class="p-6 flex space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <div class="flex-1">
                        <x-div.side-bar-admin>
                            <span class="text-gray-800">{{ $message->user->name }}</span>
                            <div class="flex items-center">
                                @unless ($message->created_at->eq($message->updated_at))
                                    <small class="text-sm text-gray-600"> &middot; {{ __('Edited') }}</small>
                                @endunless
                                <small
                                    class="text-sm text-gray-600">{{ $message->created_at->format('　Y/m/d　H:i:s') }}</small>
                                <span class="mx-1"> </span>

                                @if ($message->user->is(auth()->user()))
                                    <x-dropdown class="ml-2">
                                        <x-slot name="trigger">
                                            <button>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <x-dropdown-link :href="route('user.products.messages.edit', [
                                                'product' => $product->id,
                                                'message' => $message->id,
                                            ])">
                                                {{ __('更改') }}
                                            </x-dropdown-link>
                                            <form method="POST"
                                                action="{{ route('user.products.messages.destroy', ['product' => $product->id, 'message' => $message->id]) }}">
                                                @csrf
                                                @method('delete')
                                                <x-dropdown-link :href="route('user.products.messages.destroy', [
                                                    'product' => $product->id,
                                                    'message' => $message->id,
                                                ])"
                                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                                    {{ __('刪除') }}
                                                </x-dropdown-link>
                                            </form>
                                        </x-slot>
                                    </x-dropdown>
                                @else
                                    <x-dropdown class="ml-2">
                                        <x-slot name="trigger">
                                            <button>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <x-dropdown-link href="#"
                                                onclick="event.preventDefault(); reportMessage(event, {{ $message->id }})"
                                                data-report-type="message" data-message-id="{{ $message->id }}"
                                                data-reports="{{ json_encode($messageReports) }}"
                                                data-store-url="{{ route('api.messages.reports.store', ['message' => $message->id]) }}">
                                                {{ __('檢舉') }}
                                            </x-dropdown-link>
                                        </x-slot>
                                    </x-dropdown>
                                @endif
                            </div>
                        </x-div.side-bar-admin>
                        <p class="mt-4 text-lg text-gray-900 whitespace-pre-line">{{ $message->message }}</p>

                        <button onclick="toggleReplyForm({{ $message->id }})" class="mt-2 text-sm text-blue-500">
                            回覆
                        </button>

                        <form id="replyForm{{ $message->id }}" method="POST"
                            action="{{ route('user.products.messages.reply', ['product' => $product->id, 'message' => $message->id]) }}"
                            class="mt-2 hidden">
                            @csrf
                            <textarea name="message" placeholder="{{ __('回覆留言...') }}"
                                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                            <x-primary-button class="mt-2">{{ __('提交回覆') }}</x-primary-button>
                        </form>

                        @foreach ($message->replies as $reply)
                            <div class="ml-8 mt-4 p-4 bg-gray-50 rounded-lg">
                                <x-div.side-bar-admin>
                                    <span class="text-gray-800">{{ $reply->user->name }}</span>
                                    <div class="flex items-center">
                                        @unless ($reply->created_at->eq($reply->updated_at))
                                            <small class="text-sm text-gray-600"> &middot; {{ __('Edited') }}</small>
                                        @endunless
                                        <small
                                            class="text-sm text-gray-600">{{ $reply->created_at->format('　Y/m/d　H:i:s') }}</small>
                                        <span class="mx-1">　</span>

                                        @if ($reply->user->is(auth()->user()))
                                            <x-dropdown class="ml-2">
                                                <x-slot name="trigger">
                                                    <button>
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 text-gray-400" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path
                                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        </svg>
                                                    </button>
                                                </x-slot>
                                                <x-slot name="content">
                                                    <x-dropdown-link :href="route('user.products.messages.edit', [
                                                        'product' => $product->id,
                                                        'message' => $reply->id,
                                                    ])">
                                                        {{ __('更改') }}
                                                    </x-dropdown-link>
                                                    <form method="POST"
                                                        action="{{ route('user.products.messages.destroy', ['product' => $product->id, 'message' => $reply->id]) }}">
                                                        @csrf
                                                        @method('delete')
                                                        <x-dropdown-link :href="route('user.products.messages.destroy', [
                                                            'product' => $product->id,
                                                            'message' => $reply->id,
                                                        ])"
                                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                                            {{ __('刪除') }}
                                                        </x-dropdown-link>
                                                    </form>
                                                </x-slot>
                                            </x-dropdown>
                                        @else
                                            <x-dropdown class="ml-2">
                                                <x-slot name="trigger">
                                                    <button>
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 text-gray-400" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path
                                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        </svg>
                                                    </button>
                                                </x-slot>
                                                <x-slot name="content">
                                                    <x-dropdown-link href="#"
                                                        onclick="event.preventDefault(); reportMessage(event, {{ $reply->id }})"
                                                        data-report-type="message"
                                                        data-message-id="{{ $reply->id }}"
                                                        data-reports="{{ json_encode($messageReports) }}"
                                                        data-store-url="{{ route('api.messages.reports.store', ['message' => $reply->id]) }}">
                                                        {{ __('檢舉') }}
                                                    </x-dropdown-link>
                                                </x-slot>
                                            </x-dropdown>
                                        @endif
                                    </div>
                                </x-div.side-bar-admin>
                                <p class="mt-2 text-gray-900 whitespace-pre-line">{{ $reply->message }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        @if ($messages->hasPages())
            <div class="mt-6">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
    <script>
        function toggleReplyForm(messageId) {
            const form = document.getElementById(`replyForm${messageId}`);
            form.classList.toggle('hidden');
            const textarea = form.querySelector('textarea');
            if (!form.classList.contains('hidden')) {
                textarea.focus();
            }
        }
    </script>
</x-template-layout>
