<x-template-layout>

    <form action="{{ route('products.index') }}" method="GET">
        <div class="flex items-center justify-center gap-2 mb-4">
            <input type="text" name="filter[name]" placeholder="搜尋產品名稱..." value="{{ request('filter.name') ?? '' }}"
                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <x-button.search>
                搜尋
            </x-button.search>
        </div>
        <div class="flex flex-wrap gap-2 justify-center">
            @foreach (['科目', '課程', '年級', '學期'] as $type)
                <select name="filter[tags][]" class="bg-gray text-primary-foreground px-4 py-2 rounded-md">
                    <option value="">選擇{{ $type }}...</option>
                    @foreach ($allTags as $tag)
                        @if ($tag->type === $type)
                            <option value="{{ $tag->id }}"
                                {{ in_array($tag->id, request('filter.tags', [])) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            @endforeach
            <x-button.search>
                搜尋
            </x-button.search>
        </div>
    </form>


    <div class="flex flex-col w-full min-h-screen">
        <main class="flex min-h-[calc(100vh_-_theme(spacing.16))] flex-1 flex-col gap-4 p-4 md:gap-8 md:p-10">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $product)
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm" data-v0-t="card">
                        <div class="space-y-1.5 p-6">
                            <h4 class="font-semibold text-2xl mb-2">商品名稱:{{ $product->name }}</h4>
                            <div>
                                <h1 class="font-semibold">用戶名稱:{{ $product->user->name }}</h1>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-2xl font-bold">${{ $product->price }}</div>
                            <h1 class="font-semibold">上架時間: {{ $product->updated_at->format('Y/m/d') }}</h1>
                            <p class="font-semibold text-sm mt-2">{{ $product->description }}</p>
                            <div class="mt-4">
                                @if ($product->media->isNotEmpty())
                                    @php
                                        $media = $product->getFirstMedia('images');
                                    @endphp
                                    @if ($media)
                                        <img src="{{ $media->getUrl() }}" alt="這是圖片" width="1200" height="900"
                                            style="aspect-ratio: 900 / 1200; object-fit: cover;"
                                            class="w-full rounded-md object-cover" />
                                    @else
                                        <div>沒圖片</div>
                                    @endif
                                @else
                                    <div>沒有圖片</div>
                                @endif
                            </div>
                            <div class="flex items-center justify-between mb-8">
                                <h6 class="font-black text-gray-600 text-sm md:text-lg">年級 :
                                    <span class="font-semibold text-gray-900 text-md md:text-lg">
                                        @php
                                            $gradeTag = $product->tags->firstWhere('type', '年級');
                                            $semesterTag = $product->tags->firstWhere('type', '學期');
                                        @endphp
                                        {{ $gradeTag ? $gradeTag->name : '無' }}
                                        {{ $semesterTag ? $semesterTag->name : '學期:無' }}
                                    </span>
                                </h6>
                                <h6 class="font-black text-gray-600 text-sm md:text-lg">課程 :
                                    <span class="font-semibold text-gray-900 text-md md:text-lg">
                                        @php
                                            $categoryTag = $product->tags->firstWhere('type', '課程');
                                        @endphp
                                        {{ $categoryTag ? $categoryTag->name : '無' }}
                                    </span>
                                </h6>
                            </div>
                        </div>
                        <div class="flex items-center p-6">
                            <a href= "{{ route('products.show', ['product' => $product->id]) }}">
                                <x-button.blue-short>
                                    洽談
                                </x-button.blue-short>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </main>
    </div>
</x-template-layout>
