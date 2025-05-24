@php
    use App\Enums\Tagtype;
@endphp

<x-template-layout>
    <script src="{{ asset('js/user/products/info.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/info.css') }}">

    <div class="grid md:grid-cols-2 gap-6 lg:gap-12 items-start max-w-6xl px-4 mx-auto py-6">
        <div>
            @if ($product->media->isNotEmpty())
                <div class="relative mb-4">
                    @foreach ($product->getMedia('images') as $index => $media)
                        <img src="{{ $media->getUrl() }}" alt="Product Image {{ $index + 1 }}" width="1200"
                            height="900" style="aspect-ratio"
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

            <div class="mt-4 flex flex-wrap justify-start gap-4 md:grid md:grid-cols-5 md:gap-3">
                @foreach ($product->getMedia('images') as $index => $media)
                    <div class="w-24 h-24 md:w-auto md:h-auto aspect-square border-2 border-gray-300 flex items-center justify-center cursor-pointer thumbnail"
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
                        <x-h.h2>用戶名稱:{{ $product->user->name }}</x-h.h2>

                        <x-h.h6>年級 :
                            <x-span.font-semibold>
                                @php
                                    $gradeTag = $product->tags->firstWhere('type', Tagtype::Grade->value);
                                    $semesterTag = $product->tags->firstWhere('type', Tagtype::Semester->value);
                                @endphp
                                {{ $gradeTag ? $gradeTag->name : '無' }}
                                {{ $semesterTag ? $semesterTag->name : '學期:無' }}
                            </x-span.font-semibold>
                        </x-h.h6>

                        <x-h.h6>課程 :
                            <x-span.font-semibold>
                                @php
                                    $categoryTag = $product->tags->firstWhere('type', Tagtype::Category->value);
                                @endphp
                                {{ $categoryTag ? $categoryTag->name : '無' }}
                            </x-span.font-semibold>
                        </x-h.h6>

                        <x-h.h6>科目 :
                            <x-span.font-semibold>
                                @php
                                    $subjectTag = $product->tags->firstWhere('type', Tagtype::Subject->value);
                                @endphp
                                {{ $subjectTag ? $subjectTag->name : '無' }}
                            </x-span.font-semibold>
                        </x-h.h6>
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
            <textarea name="message" placeholder="{{ __('想問什麼?') }}">{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->store->get('message')" class="mt-2" />
            <x-primary-button>{{ __('留言') }}</x-primary-button>
        </form>

        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            @foreach ($messages as $message)
                <div id="message-{{ $message->id }}" class="p-4 mb-4 rounded-lg border border-gray-200">
                    <div class="flex items-start mb-2">
                        <div class="h-8 w-8 mr-2 flex items-center justify-center bg-gray-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span>{{ $message->user->name }}</span>
                                <x-p.text-gray>{{ $message->created_at->format('Y/m/d H:i:s') }}</x-p.text-gray>
                            </div>
                            <div class="text-xs text-gray-500 flex items-center">
                                @if ($message->trashed())
                                    <span>（此回覆已被刪除）</span>
                                @else
                                    @unless ($message->created_at->eq($message->updated_at))
                                        <span>已編輯</span>
                                    @endunless
                                @endif
                            </div>
                            @if (!$message->trashed())
                                <div class="ml-2">
                                    <div>
                                        @if ($message->user->is(auth()->user()))
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
                                                        onclick="event.preventDefault(); reportMessage(event, {{ $message->id }})"
                                                        data-report-type="message"
                                                        data-message-id="{{ $message->id }}"
                                                        data-reports="{{ json_encode($messageReports) }}"
                                                        data-store-url="{{ route('api.messages.reports.store', ['message' => $message->id]) }}">
                                                        {{ __('檢舉') }}
                                                    </x-dropdown-link>
                                                </x-slot>
                                            </x-dropdown>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($message->trashed())
                        <x-p.text-gray>（此留言已被刪除）</x-p.text-gray>
                    @else
                        <div>{{ $message->message }}</div>
                    @endif

                    <div>
                        <button onclick="toggleReplyForm({{ $message->id }})"
                            class="text-sm text-blue-500 hover:text-blue-700">
                            回覆
                        </button>
                    </div>

                    <form id="replyForm{{ $message->id }}" method="POST"
                        action="{{ route('user.products.messages.reply', ['product' => $product->id, 'message' => $message->id]) }}">
                        @csrf
                        <textarea name="message" placeholder="{{ __('回覆留言...') }}"></textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        <x-primary-button>{{ __('提交回覆') }}</x-primary-button>
                    </form>

                    <div class="replies-container">
                        @php
                            $totalReplies = $message->replies->count();
                            $hasMoreReplies = $totalReplies > 4;
                        @endphp

                        @foreach ($message->replies as $index => $reply)
                            <div id="reply-{{ $reply->id }}"
                                class="ml-8 mt-4 p-3 bg-gray-50 rounded-lg border border-gray-100 reply-item {{ $index >= 4 && $hasMoreReplies ? 'hidden' : '' }}"
                                data-message-id="{{ $message->id }}">
                                <div class="flex items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <span>{{ $reply->user->name }}</span>
                                            <x-p.text-gray>{{ $reply->created_at->format('Y/m/d H:i:s') }}</x-p.text-gray>
                                        </div>
                                        <x-p.text-gray>
                                            @unless ($reply->created_at->eq($reply->updated_at))
                                                @if (!$reply->trashed())
                                                    <span>已編輯</span>
                                                @endif
                                            @endunless
                                        </x-p.text-gray>
                                        @if (!$reply->trashed())
                                            <div class="ml-2">
                                                @if ($reply->user->is(auth()->user()))
                                                    <x-dropdown>
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
                                        @endif
                                    </div>
                                </div>
                                @if ($reply->trashed())
                                    <x-p.text-gray>（此回覆已被刪除）</x-p.text-gray>
                                @else
                                    <div>{{ $reply->message }}</div>
                                @endif
                            </div>
                        @endforeach

                        @if ($hasMoreReplies)
                            <div class="ml-8 mt-2">
                                <button type="button"
                                    class="toggle-replies text-blue-500 hover:text-blue-700 text-sm font-medium"
                                    data-message-id="{{ $message->id }}"
                                    data-is-expanded="false"
                                    data-total-hidden="{{ $totalReplies - 4 }}">
                                    查看更多留言 ({{ $totalReplies - 4 }})
                                </button>
                            </div>
                        @endif
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
</x-template-layout>
