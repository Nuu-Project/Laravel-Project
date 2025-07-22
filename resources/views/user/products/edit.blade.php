@php
    use App\Enums\Tagtype;
@endphp

<x-template-user-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/product-editor.js') }}"></script>
    <script src="{{ asset('js/tag-selector.js') }}"></script>
    <script>
        window.initialSelectedTags = {
            grade: "{{ $gradeTag ? $gradeTag->id : old('grade') }}",
            semester: "{{ $semesterTag ? $semesterTag->id : old('semester') }}",
            subject: "{{ $subjectTag ? $subjectTag->id : old('subject') }}",
            category: "{{ $categoryTag ? $categoryTag->id : old('category') }}"
        };
    </script>
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

                    <input type="hidden" name="grade" id="grade-input" value="{{ $gradeTag ? $gradeTag->id : '' }}">
                    <input type="hidden" name="semester" id="semester-input"
                        value="{{ $semesterTag ? $semesterTag->id : '' }}">
                    <input type="hidden" name="subject" id="subject-input"
                        value="{{ $subjectTag ? $subjectTag->id : '' }}">
                    <input type="hidden" name="category" id="category-input"
                        value="{{ $categoryTag ? $categoryTag->id : '' }}">

                    <div class="tag-selector-container">
                        <div class="mb-3">
                            <x-label.form for="tag-selector-button">
                                標籤選擇
                            </x-label.form>
                            <x-button.tag id="tag-selector-button">
                                <span id="selected-tags-summary">選擇標籤...</span>
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </x-button.tag>
                        </div>

                        <div id="tag-selection-popup" class="tag-selection-container hidden"
                            style="position: absolute; z-index: 50; width: 100%; max-width: 500px; box-shadow: 0 4px 12px rgba(0,0,0,0.4);">
                            <div class="milestone-selector-wrapper">
                                <div class="search-container mb-4">
                                    <input type="text" id="tagSearchInput" placeholder="搜尋標籤..."
                                        class="w-full p-2 rounded border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>

                                <div>
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

                                    <div id="semester-section">
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

                                    <div id="category-section">
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

                                    <div id="subject-section">
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
                                </div>
                            </div>

                            <x-div.flex-row>
                                <x-button.close>
                                    關閉
                                </x-button.close>
                                <x-button.clear>
                                    清除
                                </x-button.clear>
                                <x-button.apply type="submit">
                                    確認選擇
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
                            <div class="">
                                <div id="tag-progress-bar"
                                    class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                                    style="width: 0%"></div>
                            </div>
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

                        <x-div.picture id="imageContainer">
                            @for ($i = 0; $i < 5; $i++)
                                <x-product.image-uploader-edit :index="$i" :product-id="$product->id" :image-id="$product->getMedia('images')->sortBy('order_column')->values()->get($i)?->id"
                                    :image-url="$product
                                        ->getMedia('images')
                                        ->sortBy('order_column')
                                        ->values()
                                        ->get($i)
                                        ?->getUrl()" />
                            @endfor
                        </x-div.picture>
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                    </x-div.grid>

                    <x-button.submit>
                        儲存修改
                    </x-button.submit>
                </form>
            </x-div.grid>
        </x-div.container>
    </x-flex-container>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
</x-template-user-layout>
