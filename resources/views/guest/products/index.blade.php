@php
    use App\Enums\Tagtype;
@endphp

<x-template-layout>
    <link rel="stylesheet" href="{{ asset('css/milestone-selector.css') }}">

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

        @foreach (collect(Tagtype::cases())->pluck('value') as $type)
            <input type="hidden" id="{{ $type }}-input" name="filter[tags][]"
                value="{{ in_array(request('filter.tags', []), []) ? '' : request('filter.tags', [])[array_search($type, collect(Tagtype::cases())->pluck('value')->toArray())] ?? '' }}">
        @endforeach

        <div class="tag-selector-container mx-auto max-w-3xl mb-6">
            <div class="mb-3">
                <button type="button" id="tag-selector-button"
                    class="tag-selector-button w-full text-left p-3 bg-white rounded-md flex justify-between items-center border border-gray-300 hover:border-gray-400">
                    <span id="selected-tags-summary">選擇標籤...</span>
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>

            <div id="tag-selection-popup" class="tag-selection-container hidden"
                style="position: absolute; z-index: 50; width: 100%; max-width: 500px; box-shadow: 0 4px 12px rgba(0,0,0,0.4);">
                <div class="milestone-selector-wrapper">
                    <div class="search-container mb-4">
                        <input type="text" id="tagSearchInput" placeholder="搜尋標籤..."
                            class="w-full p-2 rounded border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="milestone-content">
                        @foreach (collect(Tagtype::cases())->pluck('value') as $type)
                            <div class="milestone-section" id="{{ $type }}-section">
                                <h3 class="milestone-header">{{ $type }}</h3>
                                <div class="milestone-options">
                                    @foreach ($allTags as $tag)
                                        @if ($tag->type === $type)
                                            <div class="milestone-option" data-tag-id="{{ $tag->id }}"
                                                data-tag-type="{{ $type }}" data-tag-name="{{ $tag->name }}">
                                                <span class="tag-icon">
                                                    @if ($type === 'grade')
                                                        📚
                                                    @elseif ($type === 'semester')
                                                        🗓️
                                                    @elseif ($type === 'subject')
                                                        📝
                                                    @elseif ($type === 'category')
                                                        📋
                                                    @else
                                                        🏷️
                                                    @endif
                                                </span>
                                                <span class="tag-name">{{ $tag->name }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <x-button.clear>
                        清除所有
                    </x-button.clear>
                    <x-button.close>
                        關閉
                    </x-button.close>
                    <x-button.apply>
                        查詢
                    </x-button.apply>
                </div>
            </div>

            <div class="selected-tags-summary mt-2 flex flex-wrap gap-2">
                @foreach (collect(Tagtype::cases())->pluck('value') as $type)
                    <div id="selected-{{ $type }}-pill" class="tag-pill hidden"></div>
                @endforeach
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
        document.addEventListener('DOMContentLoaded', function() {
            const tagSelectorButton = document.getElementById('tag-selector-button');
            const tagSelectionPopup = document.getElementById('tag-selection-popup');
            const closeTagSelector = document.getElementById('close-tag-selector');
            const applyTagFilters = document.getElementById('apply-tag-filters');
            const clearTagSelection = document.getElementById('clear-tag-selection');
            const selectedTagsSummary = document.getElementById('selected-tags-summary');
            const filterForm = document.getElementById('filterForm');

            let selectedTags = {};

            @foreach (collect(Tagtype::cases())->pluck('value') as $type)
                selectedTags["{{ $type }}"] = {
                    id: document.getElementById("{{ $type }}-input").value || null,
                    name: '',
                    selected: !!document.getElementById("{{ $type }}-input").value
                };
            @endforeach

            initializeSelectedTags();
            updateTagsSummary();

            tagSelectorButton.addEventListener('click', function() {
                tagSelectionPopup.classList.remove('hidden');
                positionPopup();
            });

            closeTagSelector.addEventListener('click', function() {
                tagSelectionPopup.classList.add('hidden');
            });

            clearTagSelection.addEventListener('click', function() {
                document.querySelectorAll('.milestone-option.selected').forEach(el => {
                    el.classList.remove('selected');
                });

                Object.keys(selectedTags).forEach(type => {
                    selectedTags[type] = {
                        id: null,
                        name: '',
                        selected: false
                    };
                    document.getElementById(`${type}-input`).value = '';
                    document.getElementById(`selected-${type}-pill`).classList.add('hidden');
                });

                updateTagsSummary();
            });

            applyTagFilters.addEventListener('click', function() {
                tagSelectionPopup.classList.add('hidden');
                filterForm.submit();
            });

            document.addEventListener('click', function(event) {
                if (!tagSelectionPopup.contains(event.target) &&
                    !tagSelectorButton.contains(event.target) &&
                    !tagSelectionPopup.classList.contains('hidden')) {
                    tagSelectionPopup.classList.add('hidden');
                }
            });

            const tagOptions = document.querySelectorAll('.milestone-option');
            tagOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const tagType = this.dataset.tagType;
                    const tagId = this.dataset.tagId;
                    const tagName = this.dataset.tagName;

                    document.querySelectorAll(`.milestone-option[data-tag-type="${tagType}"]`)
                        .forEach(el => {
                            el.classList.remove('selected');
                        });

                    this.classList.add('selected');

                    document.getElementById(`${tagType}-input`).value = tagId;

                    selectedTags[tagType] = {
                        id: tagId,
                        name: tagName,
                        selected: true
                    };

                    updateSelectedTagPills();
                });
            });

            const searchInput = document.getElementById('tagSearchInput');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                tagOptions.forEach(option => {
                    const tagName = option.dataset.tagName.toLowerCase();
                    if (tagName.includes(searchTerm)) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });

            function initializeSelectedTags() {
                @foreach (collect(Tagtype::cases())->pluck('value') as $type)
                    if (document.getElementById("{{ $type }}-input").value) {
                        const option = document.querySelector(
                            `.milestone-option[data-tag-type="{{ $type }}"][data-tag-id="${document.getElementById("{{ $type }}-input").value}"]`
                        );
                        if (option) {
                            option.classList.add('selected');
                            selectedTags["{{ $type }}"] = {
                                id: option.dataset.tagId,
                                name: option.dataset.tagName,
                                selected: true
                            };
                        }
                    }
                @endforeach

                updateSelectedTagPills();
            }

            function updateSelectedTagPills() {
                Object.keys(selectedTags).forEach(type => {
                    const tag = selectedTags[type];
                    const pill = document.getElementById(`selected-${type}-pill`);

                    if (tag.selected) {
                        pill.innerHTML = `
                            <span class="tag-icon">${getTagIcon(type)}</span>
                            <span>${tag.name}</span>
                        `;
                        pill.classList.remove('hidden');
                    } else {
                        pill.classList.add('hidden');
                    }
                });
            }

            function updateTagsSummary() {
                const selectedCount = Object.values(selectedTags).filter(tag => tag.selected).length;

                if (selectedCount === 0) {
                    selectedTagsSummary.textContent = '選擇標籤...';
                } else {
                    selectedTagsSummary.textContent = `已選擇 ${selectedCount} 個標籤`;
                }
            }

            function positionPopup() {
                const buttonRect = tagSelectorButton.getBoundingClientRect();
                const popupHeight = tagSelectionPopup.offsetHeight;
                const windowHeight = window.innerHeight;

                if (buttonRect.bottom + popupHeight > windowHeight) {
                    tagSelectionPopup.style.top = (buttonRect.top - popupHeight) + 'px';
                } else {
                    tagSelectionPopup.style.top = buttonRect.bottom + 'px';
                }

                tagSelectionPopup.style.left = buttonRect.left + 'px';
            }

            function getTagIcon(tagType) {
                switch (tagType) {
                    case 'grade':
                        return '📚';
                    case 'semester':
                        return '🗓️';
                    case 'subject':
                        return '📝';
                    case 'category':
                        return '📋';
                    default:
                        return '🏷️';
                }
            }

            window.addEventListener('resize', function() {
                if (!tagSelectionPopup.classList.contains('hidden')) {
                    positionPopup();
                }
            });
        });
        feather.replace()
    </script>
</x-template-layout>
