@php
    use App\Enums\Tagtype;
@endphp

<x-template-layout>
    <link rel="stylesheet" href="{{ asset('css/milestone-selector.css') }}">
    <script src="{{ asset('js/tag-selector.js') }}"></script>
    @php
        $typeMapping = [
            '年級' => 'grade',
            '學期' => 'semester',
            '課程' => 'subject',
            '科目' => 'category',
        ];

        $selectedTags = request('filter.tags', []);
        $initialTags = [];

        $reverseTypeMapping = array_flip($typeMapping);

        foreach ($allTags as $tag) {
            if (in_array((string) $tag->id, $selectedTags)) {
                $mappedType = $typeMapping[$tag->type] ?? null;
                if ($mappedType) {
                    $initialTags[$mappedType] = (string) $tag->id;
                }
            }
        }
    @endphp

    <script>
        window.initialSelectedTags = @json($initialTags);
        console.log('Initial Selected Tags:', window.initialSelectedTags);
    </script>

    <form action="{{ route('products.index') }}" method="GET" id="filterForm">
        <div class="flex items-center justify-center gap-2 mb-4">
            <x-form.search-layout>
                <x-responsive.container>
                    <x-input.search type="text" name="filter[name]" placeholder="搜尋商品名稱..."
                        value="{{ request('filter.name') }}">
                    </x-input.search>
                </x-responsive.container>
                <x-button.search>
                    搜尋
                </x-button.search>
            </x-form.search-layout>
        </div>

        @foreach ($typeMapping as $chineseType => $englishType)
            <input type="hidden" id="{{ $englishType }}-input" name="filter[tags][]"
                value="{{ isset($initialTags[$englishType]) ? $initialTags[$englishType] : '' }}">
        @endforeach

        <div class="tag-selector-container mx-auto max-w-3xl mb-6">
            <div class="mb-3">
                <x-button.tag id="tag-selector-button">
                    <span id="selected-tags-summary">選擇標籤...</span>
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </x-button.tag>
            </div>

            <div id="tag-selection-popup" class="tag-selection-container hidden"
                style="position: absolute; z-index: 50; width: 100%; max-width: 500px; box-shadow: 0 4px 12px rgba(0,0,0,0.4);">
                <div class="milestone-selector-wrapper">
                    <div class="search-container mb-4">
                        <input type="text" id="tagSearchInput" placeholder="搜尋標籤..." class="">
                    </div>
                    <div>
                        @foreach ($typeMapping as $chineseType => $englishType)
                            <div id="{{ $englishType }}-section">
                                <h3>{{ $chineseType }}</h3>
                                <div>
                                    @foreach ($allTags as $tag)
                                        @if ($tag->type === $chineseType)
                                            <div class="milestone-option" data-tag-id="{{ $tag->id }}"
                                                data-tag-type="{{ $englishType }}"
                                                data-tag-name="{{ is_array($tag->name) ? $tag->name['zh_TW'] : $tag->name }}">
                                                <span>
                                                    @if ($englishType === 'grade')
                                                        📚
                                                    @elseif ($englishType === 'semester')
                                                        🗓️
                                                    @elseif ($englishType === 'subject')
                                                        📝
                                                    @elseif ($englishType === 'category')
                                                        📋
                                                    @else
                                                        🏷️
                                                    @endif
                                                </span>
                                                <span>{{ is_array($tag->name) ? $tag->name['zh_TW'] : $tag->name }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <x-div.flex-row>
                    <x-button.clear>
                        清除所有
                    </x-button.clear>
                    <x-button.close>
                        關閉
                    </x-button.close>
                    <x-button.apply type="submit">
                        查詢
                    </x-button.apply>
                </x-div.flex-row>
            </div>

            <div id="selected-tags-display" class="mt-2 flex flex-wrap gap-2">
            </div>

            <div id="tag-progress" class="hidden mt-4">
                <div class="flex justify-between items-center mb-1">
                    <x-span.text-sm>已選擇標籤</x-span.text-sm>
                    <x-span.text-sm id="tag-progress-percentage">0%</x-span.text-sm>
                </div>
                <div class="w-full bg-blue-500 rounded-full h-2.5">
                    <div id="tag-progress-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                        style="width: 0%"></div>
                </div>
            </div>
        </div>
    </form>


    <div class="container mx-auto">
        <main class="py-6">
            <x-product.card :products="$products" />

            <div class="mt-6">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </main>
    </div>

    <script>
        feather.replace()
    </script>
</x-template-layout>
