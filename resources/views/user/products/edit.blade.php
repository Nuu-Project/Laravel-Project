@php
    use App\Enums\Tagtype;
@endphp

<x-template-user-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/product-editor.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/milestone-selector.css') }}">

    <x-flex-container>
        <x-div.container>
            <x-div.grid>
                <x-div.grid>
                    <x-h.h1-middle>修改刊登商品</x-h.h1-middle>
                    <x-p.text-muted>請依照下順序進行填寫，照片上傳張數最多五張。</x-p.text-muted>
                    <x-p.text-muted>圖片最左邊將會是商品首圖。</x-p.text-muted>
                </x-div.grid>

                @if ($errors->any())
                    <x-div.red role="alert">
                        <strong>驗證錯誤！</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-div.red>
                @endif

                <form id="productForm" class="grid gap-6"
                    action="{{ route('user.products.update', ['product' => $product->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="imageOrder" id="imageOrder" value="[]">
                    <input type="hidden" name="deleted_image_ids" id="deletedImageIds" value="[]">
                    <x-div.grid>
                        <x-label.form for="name">
                            書名
                        </x-label.form>
                        <x-input.tags id="name" name="name" placeholder="請輸入書名" value="{{ $product->name }}"
                            maxlength="50" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <x-label.form for="price">
                            價格 (不可重複修改)
                        </x-label.form>
                        <x-input.tags id="price" name="price" placeholder="輸入價格" type="number"
                            value="{{ $product->price }}" readonly />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </x-div.grid>

                    <!-- 隱藏的標籤輸入欄位 -->
                    <input type="hidden" name="grade" id="grade-input" value="{{ $gradeTag ? $gradeTag->id : '' }}">
                    <input type="hidden" name="semester" id="semester-input"
                        value="{{ $semesterTag ? $semesterTag->id : '' }}">
                    <input type="hidden" name="subject" id="subject-input"
                        value="{{ $subjectTag ? $subjectTag->id : '' }}">
                    <input type="hidden" name="category" id="category-input"
                        value="{{ $categoryTag ? $categoryTag->id : '' }}">

                    <!-- 標籤選擇按鈕和彈出框 -->
                    <div class="tag-selector-container">
                        <div class="mb-3">
                            <x-label.form for="tag-selector-button">
                                標籤選擇
                            </x-label.form>
                            <button type="button" id="tag-selector-button"
                                class="tag-selector-button w-full text-left p-3 bg-white rounded-md flex justify-between items-center border border-gray-300 hover:border-gray-400">
                                <span id="selected-tags-summary">選擇標籤...</span>
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- 標籤選擇彈出層 -->
                        <div id="tag-selection-popup" class="tag-selection-container hidden"
                            style="position: absolute; z-index: 50; width: 100%; max-width: 500px; box-shadow: 0 4px 12px rgba(0,0,0,0.4);">
                            <div class="milestone-selector-wrapper">
                                <!-- 標籤搜尋欄 -->
                                <div class="search-container mb-4">
                                    <input type="text" id="tagSearchInput" placeholder="搜尋標籤..."
                                        class="w-full p-2 rounded border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>

                                <!-- 標籤選擇區域 -->
                                <div class="milestone-content">
                                    <!-- 年級標籤選擇 -->
                                    <div class="milestone-section" id="grade-section">
                                        <h3>年級</h3>
                                        <div class="milestone-options">
                                            @foreach ($tags as $tag)
                                                @if ($tag->type === Tagtype::Grade->value)
                                                    <div class="milestone-option" data-tag-id="{{ $tag->id }}"
                                                        data-tag-type="grade" data-tag-name="{{ $tag->name }}">
                                                        <span>📚</span>
                                                        <span>{{ $tag->name }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                                    </div>

                                    <!-- 學期標籤選擇 -->
                                    <div class="milestone-section" id="semester-section">
                                        <h3>學期</h3>
                                        <div class="milestone-options">
                                            @foreach ($tags as $tag)
                                                @if ($tag->type === Tagtype::Semester->value)
                                                    <div class="milestone-option" data-tag-id="{{ $tag->id }}"
                                                        data-tag-type="semester" data-tag-name="{{ $tag->name }}">
                                                        <span>🗓️</span>
                                                        <span>{{ $tag->name }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                                    </div>

                                    <!-- 科目標籤選擇 -->
                                    <div class="milestone-section" id="subject-section">
                                        <h3>科目</h3>
                                        <div class="milestone-options">
                                            @foreach ($tags as $tag)
                                                @if ($tag->type === Tagtype::Subject->value)
                                                    <div class="milestone-option" data-tag-id="{{ $tag->id }}"
                                                        data-tag-type="subject" data-tag-name="{{ $tag->name }}">
                                                        <span>📝</span>
                                                        <span>{{ $tag->name }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                                    </div>

                                    <div class="milestone-section" id="category-section">
                                        <h3>課程類別</h3>
                                        <div class="milestone-options">
                                            @foreach ($tags as $tag)
                                                @if ($tag->type === Tagtype::Category->value)
                                                    <div class="milestone-option" data-tag-id="{{ $tag->id }}"
                                                        data-tag-type="category" data-tag-name="{{ $tag->name }}">
                                                        <span>📋</span>
                                                        <span>{{ $tag->name }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <x-div.flex-row>
                                <x-button.close>
                                    關閉
                                </x-button.close>
                                <x-button.select>
                                    確認選擇
                                </x-button.select>
                            </x-div.flex-row>
                        </div>

                        <div class="selected-tags-summary mt-2 flex flex-wrap gap-2">
                            <div id="selected-grade-pill" class="tag-pill hidden"></div>
                            <div id="selected-semester-pill" class="tag-pill hidden"></div>
                            <div id="selected-subject-pill" class="tag-pill hidden"></div>
                            <div id="selected-category-pill" class="tag-pill hidden"></div>
                        </div>
                    </div>

                    <x-div.grid>
                        <div class="space-y-2">
                            <x-input-label for="description" :value="__('商品描述')" />
                            <x-input.textarea id="description" name="description" placeholder="請輸入商品描述"
                                rows="4" maxlength="1000" :value="old('description', $product->description)" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </x-div.grid>
                    <x-div.grid>
                        <x-label.form for="image">
                            上傳圖片
                        </x-label.form>

                        <div id="imageContainer"
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @for ($i = 0; $i < 5; $i++)
                                <x-product.image-uploader-edit :index="$i" :product-id="$product->id" :image-id="$product->getMedia('images')->sortBy('order_column')->values()->get($i)?->id"
                                    :image-url="$product
                                        ->getMedia('images')
                                        ->sortBy('order_column')
                                        ->values()
                                        ->get($i)
                                        ?->getUrl()" />
                            @endfor
                        </div>
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                    </x-div.grid>

                    <x-button.submit>
                        儲存修改
                    </x-button.submit>
                </form>
            </x-div.grid>
        </x-div.container>
    </x-flex-container>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 處理彈出式標籤選擇器
            const tagSelectorButton = document.getElementById('tag-selector-button');
            const tagSelectionPopup = document.getElementById('tag-selection-popup');
            const closeTagSelector = document.getElementById('close-tag-selector');
            const confirmTagSelection = document.getElementById('confirm-tag-selection');
            const selectedTagsSummary = document.getElementById('selected-tags-summary');
            const productForm = document.getElementById('productForm');

            // 獲取隱藏輸入欄位
            const gradeInput = document.getElementById('grade-input');
            const semesterInput = document.getElementById('semester-input');
            const subjectInput = document.getElementById('subject-input');
            const categoryInput = document.getElementById('category-input');

            // 存儲選擇的標籤
            let selectedTags = {
                grade: {
                    id: gradeInput.value || null,
                    name: '',
                    selected: !!gradeInput.value
                },
                semester: {
                    id: semesterInput.value || null,
                    name: '',
                    selected: !!semesterInput.value
                },
                subject: {
                    id: subjectInput.value || null,
                    name: '',
                    selected: !!subjectInput.value
                },
                category: {
                    id: categoryInput.value || null,
                    name: '',
                    selected: !!categoryInput.value
                }
            };

            // 初始化已選中的標籤
            initializeSelectedTags();
            updateTagsSummary();

            // 顯示標籤選擇器
            tagSelectorButton.addEventListener('click', function() {
                tagSelectionPopup.classList.remove('hidden');
                // 定位彈出窗口
                positionPopup();
            });

            // 關閉標籤選擇器
            closeTagSelector.addEventListener('click', function() {
                tagSelectionPopup.classList.add('hidden');
            });

            // 確認標籤選擇
            confirmTagSelection.addEventListener('click', function() {
                // 檢查是否有選擇所有必需標籤
                const allSelected = Object.values(selectedTags).every(tag => tag.selected);
                if (!allSelected) {
                    alert('請選擇所有標籤類別');
                    return;
                }

                tagSelectionPopup.classList.add('hidden');
                updateTagsSummary();
            });

            // 點擊其他區域關閉彈出層
            document.addEventListener('click', function(event) {
                if (!tagSelectionPopup.contains(event.target) &&
                    !tagSelectorButton.contains(event.target) &&
                    !tagSelectionPopup.classList.contains('hidden')) {
                    tagSelectionPopup.classList.add('hidden');
                }
            });

            // 表單提交前檢查標籤是否已選擇
            productForm.addEventListener('submit', function(event) {
                // 檢查是否所有必填標籤都已選擇
                const missingTags = [];

                Object.keys(selectedTags).forEach(type => {
                    if (!selectedTags[type].selected) {
                        missingTags.push(getTagTypeName(type));
                    }
                });

                if (missingTags.length > 0) {
                    event.preventDefault(); // 阻止表單提交
                    alert(`請選擇以下標籤：${missingTags.join('、')}`);
                    tagSelectionPopup.classList.remove('hidden');
                    positionPopup();
                }
            });

            // 標籤選擇功能
            const tagOptions = document.querySelectorAll('.milestone-option');
            tagOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const tagType = this.dataset.tagType;
                    const tagId = this.dataset.tagId;
                    const tagName = this.dataset.tagName;

                    // 移除同類標籤的選中狀態
                    document.querySelectorAll(`.milestone-option[data-tag-type="${tagType}"]`)
                        .forEach(el => {
                            el.classList.remove('selected');
                        });

                    // 添加選中狀態
                    this.classList.add('selected');

                    // 更新隱藏輸入欄位
                    updateHiddenInput(tagType, tagId);

                    // 更新已選擇的標籤
                    selectedTags[tagType] = {
                        id: tagId,
                        name: tagName,
                        selected: true
                    };

                    // 更新已選擇標籤的顯示
                    updateSelectedTagPills();
                });
            });

            // 更新隱藏輸入欄位
            function updateHiddenInput(type, value) {
                switch (type) {
                    case 'grade':
                        gradeInput.value = value;
                        break;
                    case 'semester':
                        semesterInput.value = value;
                        break;
                    case 'subject':
                        subjectInput.value = value;
                        break;
                    case 'category':
                        categoryInput.value = value;
                        break;
                }
            }

            // 搜尋過濾功能
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

            // 初始化已選擇的標籤
            function initializeSelectedTags() {
                // 檢查grade輸入欄位
                if (gradeInput.value) {
                    const option = document.querySelector(
                        `.milestone-option[data-tag-type="grade"][data-tag-id="${gradeInput.value}"]`);
                    if (option) {
                        option.classList.add('selected');
                        selectedTags.grade = {
                            id: option.dataset.tagId,
                            name: option.dataset.tagName,
                            selected: true
                        };
                    }
                }

                // 檢查semester輸入欄位
                if (semesterInput.value) {
                    const option = document.querySelector(
                        `.milestone-option[data-tag-type="semester"][data-tag-id="${semesterInput.value}"]`);
                    if (option) {
                        option.classList.add('selected');
                        selectedTags.semester = {
                            id: option.dataset.tagId,
                            name: option.dataset.tagName,
                            selected: true
                        };
                    }
                }

                // 檢查subject輸入欄位
                if (subjectInput.value) {
                    const option = document.querySelector(
                        `.milestone-option[data-tag-type="subject"][data-tag-id="${subjectInput.value}"]`);
                    if (option) {
                        option.classList.add('selected');
                        selectedTags.subject = {
                            id: option.dataset.tagId,
                            name: option.dataset.tagName,
                            selected: true
                        };
                    }
                }

                // 檢查category輸入欄位
                if (categoryInput.value) {
                    const option = document.querySelector(
                        `.milestone-option[data-tag-type="category"][data-tag-id="${categoryInput.value}"]`);
                    if (option) {
                        option.classList.add('selected');
                        selectedTags.category = {
                            id: option.dataset.tagId,
                            name: option.dataset.tagName,
                            selected: true
                        };
                    }
                }

                updateSelectedTagPills();
            }

            // 更新已選標籤顯示區
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

            // 更新總摘要顯示
            function updateTagsSummary() {
                const selectedCount = Object.values(selectedTags).filter(tag => tag.selected).length;

                if (selectedCount === 0) {
                    selectedTagsSummary.textContent = '選擇標籤...';
                } else if (selectedCount === 4) {
                    selectedTagsSummary.textContent = '所有標籤已選擇';
                } else {
                    selectedTagsSummary.textContent = `已選擇 ${selectedCount}/4 個標籤`;
                }
            }

            // 定位彈出窗口
            function positionPopup() {
                const buttonRect = tagSelectorButton.getBoundingClientRect();
                const popupHeight = tagSelectionPopup.offsetHeight;
                const windowHeight = window.innerHeight;

                // 檢查下方空間是否足夠
                if (buttonRect.bottom + popupHeight > windowHeight) {
                    // 如果下方空間不足，顯示在按鈕上方
                    tagSelectionPopup.style.top = (buttonRect.top - popupHeight) + 'px';
                } else {
                    // 否則顯示在按鈕下方
                    tagSelectionPopup.style.top = buttonRect.bottom + 'px';
                }

                tagSelectionPopup.style.left = buttonRect.left + 'px';
            }

            // 標籤圖標輔助函數
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

            // 獲取標籤類型的中文名稱
            function getTagTypeName(tagType) {
                switch (tagType) {
                    case 'grade':
                        return '年級';
                    case 'semester':
                        return '學期';
                    case 'subject':
                        return '科目';
                    case 'category':
                        return '課程類別';
                    default:
                        return '標籤';
                }
            }

            // 窗口大小變化時重新定位彈出窗口
            window.addEventListener('resize', function() {
                if (!tagSelectionPopup.classList.contains('hidden')) {
                    positionPopup();
                }
            });
        });
    </script>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
</x-template-user-layout>
