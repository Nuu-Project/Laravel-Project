<x-template-layout>

<body class="font-body">

    <x-navbar />
    <!-- 新增：搜索表單 -->
    <form action="{{ route('products.index') }}" method="GET" class="mb-4">
        <div class="flex items-center justify-center gap-2">
            <input type="text" name="search" placeholder="搜索產品名稱..." value="{{ $search ?? '' }}"
                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300 ease-in-out">搜索</button>
        </div>

        <!-- 保留現有的標籤選擇 -->
        @foreach ($tagSlugs as $tagSlug)
            <input type="hidden" name="tags[]" value="{{ $tagSlug }}">
        @endforeach
    </form>
    <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap gap-2 justify-center">
        <select id="subject" name="tags[]" class="bg-gray text-primary-foreground px-4 py-2 rounded-md">
            <option value="">選擇科目...</option>
            @foreach ($allTags as $tag)
                @if ($tag->type === '科目')
                    <option value="{{ $tag->getTranslation('slug', 'zh') }}"
                        {{ in_array($tag->getTranslation('slug', 'zh'), $tagSlugs) ? 'selected' : '' }}>
                        {{ $tag->getTranslation('name', 'zh') }}
                    </option>
                @endif
            @endforeach
        </select>
        <select id="category" name="tags[]" class="bg-gray text-primary-foreground px-4 py-2 rounded-md">
            <option value="">選擇課程...</option>
            @foreach ($allTags as $tag)
                @if ($tag->type === '課程')
                    <option value="{{ $tag->getTranslation('slug', 'zh') }}"
                        {{ in_array($tag->getTranslation('slug', 'zh'), $tagSlugs) ? 'selected' : '' }}>
                        {{ $tag->getTranslation('name', 'zh') }}
                    </option>
                @endif
            @endforeach
        </select>
        <select id="grade" name="tags[]" class="bg-gray text-primary-foreground px-4 py-2 rounded-md">
            <option value="">選擇年級...</option>
            @foreach ($allTags as $tag)
                @if ($tag->type === '年級')
                    <option value="{{ $tag->getTranslation('slug', 'zh') }}"
                        {{ in_array($tag->getTranslation('slug', 'zh'), $tagSlugs) ? 'selected' : '' }}>
                        {{ $tag->getTranslation('name', 'zh') }}
                    </option>
                @endif
            @endforeach
        </select>
        <select id="semester" name="tags[]" class="bg-gray text-primary-foreground px-4 py-2 rounded-md">
            <option value="">選擇學期...</option>
            @foreach ($allTags as $tag)
                @if ($tag->type === '學期')
                    <option value="{{ $tag->getTranslation('slug', 'zh') }}"
                        {{ in_array($tag->getTranslation('slug', 'zh'), $tagSlugs) ? 'selected' : '' }}>
                        {{ $tag->getTranslation('name', 'zh') }}
                    </option>
                @endif
            @endforeach
        </select>

        <button
            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition ease-in-out duration-300">
            搜索
        </button>
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
                                        {{ $gradeTag ? $gradeTag->getTranslation('name', 'zh') : '無' }}
                                        {{ $semesterTag ? $semesterTag->getTranslation('name', 'zh') : '學期:無' }}
                                    </span>
                                </h6>
                                <h6 class="font-black text-gray-600 text-sm md:text-lg">課程 :
                                    <span class="font-semibold text-gray-900 text-md md:text-lg">
                                        @php
                                            $categoryTag = $product->tags->firstWhere('type', '課程');
                                        @endphp
                                        {{ $categoryTag ? $categoryTag->getTranslation('name', 'zh') : '無' }}
                                    </span>
                                </h6>
                            </div>
                        </div>
                        <div class="flex items-center p-6">
                            <a href= "{{ route('products.show', ['product' => $product->id]) }}"
                                class="inline-flex items-center justify-center whitespace-nowrap rounded-xl
                                        text-lg font-semibold ring-offset-background
                                        transition-colors ease-in-out duration-500 focus-visible:outline-none
                                        focus-visible:ring-2 focus-visible:ring-ring
                                        focus-visible:ring-offset-2 disabled:pointer-events-none
                                        disabled:opacity-50 bg-blue-500 text-white hover:bg-blue-700 h-10 px-3 py-2 ml-auto">
                                洽談
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
