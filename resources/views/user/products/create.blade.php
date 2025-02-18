@php
    use App\Enums\Tagtype;
@endphp

<x-template-user-layout>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <div class="grid gap-6 md:gap-8">
                <x-div.grid>
                    <h1 class="text-2xl sm:text-3xl font-bold">新增刊登商品</h1>
                    <p class="text-sm sm:text-base text-muted-foreground">請依照下順序進行填寫，照片上傳張數最多五張。</p>
                    <p class="text-sm sm:text-base text-muted-foreground">圖片最左邊將會是商品首圖。</p>
                </x-div.grid>

                <!-- 驗證錯誤顯示 -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">驗證錯誤！</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="grid gap-6" action="{{ route('user.products.store') }}" method="POST"
                    enctype="multipart/form-data" id="productForm">
                    @csrf
                    <input type="hidden" name="imageOrder" id="imageOrder">
                    <x-div.grid>
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="name">
                            書名
                        </label>
                        <input
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            id="name" name="name" placeholder="請輸入書名" maxlength="50"
                            value="{{ old('name') }}" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="price">
                            價格 (不可修改)
                        </label>
                        <input
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            id="price" name="price" placeholder="輸入價格" type="number"
                            value="{{ old('price') }}" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <label class="text-sm font-medium leading-none" for="grade">年級</label>
                        <select id="grade" name="grade"
                            class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            <option value="">選擇適用的年級...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Grade->value)
                                    <option value="{{ $tag->id }}"
                                        {{ old('grade') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <label class="text-sm font-medium leading-none" for="semester">學期</label>
                        <select id="semester" name="semester"
                            class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            <option value="">選擇學期...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Semester->value)
                                    <option value="{{ $tag->id }}"
                                        {{ old('semester') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <label class="text-sm font-medium leading-none" for="subject">科目</label>
                        <select id="subject" name="subject"
                            class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            <option value="">選擇科目...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Subject->value)
                                    <option value="{{ $tag->id }}"
                                        {{ old('subject') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <label class="text-sm font-medium leading-none" for="category">課程類別</label>
                        <select id="category" name="category"
                            class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            <option value="">選擇課程類別...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Category->value)
                                    <option value="{{ $tag->id }}"
                                        {{ old('category') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="description">
                            商品介紹 (最長50字)
                        </label>
                        <textarea
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            id="description" name="description" placeholder="請填寫有關該書的書況or使用情況等等~~" rows="4" maxlength="50">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="image">
                            上傳圖片
                        </label>
                        <div id="imageContainer"
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @for ($i = 0; $i < 5; $i++)
                                <div class="relative">
                                    <input type="file" name="images[]" id="image{{ $i }}" class="hidden"
                                        accept="image/*" onchange="previewImage(this, {{ $i }})">
                                    <label for="image{{ $i }}"
                                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div id="placeholder{{ $i }}"
                                            class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span
                                                    class="font-semibold">點擊上傳</span>或拖曳</p>
                                            <p class="text-xs text-gray-500">PNG,JPG,JPEG,GIF (最大.
                                                3200x3200px, 2MB)</p>
                                        </div>
                                        <div id="preview{{ $i }}"
                                            class="absolute inset-0 flex items-center justify-center hidden">
                                            <img src="#" alt="檔案過大或格式有誤"
                                                class="max-w-full max-h-full object-contain">
                                        </div>
                                    </label>
                                    <button type="button"
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 m-1 hidden"
                                        id="deleteButton{{ $i }}"
                                        onclick="removeImage({{ $i }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endfor
                        </div>
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                    </x-div.grid>
                    <x-button.create-edit>
                        刊登商品
                    </x-button.create-edit>
                </form>
            </div>
        </x-div.container>
    </x-flex-container>
    </div>
    </div>

    <script>
        // 儲存已處理的圖片路徑
        let processedImagePaths = new Array(5).fill(null);

        async function previewImage(input, number) {
            const preview = document.getElementById('preview' + number);
            const placeholder = document.getElementById('placeholder' + number);
            const deleteButton = document.getElementById('deleteButton' + number);
            const file = input.files[0];

            if (file) {
                try {
                    // 建立 FormData 物件
                    const formData = new FormData();
                    formData.append('image', file);

                    // 發送圖片到處理 API
                    const response = await fetch('/api/products/process-image', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        // 儲存加密後的圖片路徑
                        processedImagePaths[number] = result.path;

                        // 顯示預覽圖
                        const reader = new FileReader();
                        reader.onloadend = function() {
                            preview.querySelector('img').src = reader.result;
                            preview.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                            deleteButton.classList.remove('hidden');
                        }
                        reader.readAsDataURL(file);
                    } else {
                        throw new Error('圖片處理失敗');
                    }
                } catch (error) {
                    console.error('圖片上傳失敗:', error);
                    alert('圖片上傳失敗，請重試');
                    removeImage(number);
                }
            } else {
                removeImage(number);
            }
        }

        function removeImage(index) {
            const preview = document.getElementById(`preview${index}`);
            const placeholder = document.getElementById(`placeholder${index}`);
            const imageInput = document.getElementById(`image${index}`);
            const deleteButton = document.getElementById(`deleteButton${index}`);

            preview.querySelector('img').src = '#';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            imageInput.value = '';
            deleteButton.classList.add('hidden');

            // 清除已處理的圖片路徑
            processedImagePaths[index] = null;
        }

        // 監聽表單提交
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // 檢查是否有處理過的圖片
            const hasProcessedImages = processedImagePaths.some(path => path !== null);
            if (!hasProcessedImages) {
                alert('請至少上傳一張商品圖片');
                return;
            }

            // 移除所有舊的隱藏輸入欄位
            const oldInputs = this.querySelectorAll('input[name^="encrypted_image_path"]');
            oldInputs.forEach(input => input.remove());

            // 為每個處理過的圖片創建隱藏的輸入欄位
            processedImagePaths.forEach((path, index) => {
                if (path) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `encrypted_image_path[${index}]`;
                    input.value = path;
                    this.appendChild(input);
                }
            });

            // 提交表單
            this.submit();
        });

        // 拖曳功能
        function initializeDragAndDrop() {
            const imageContainer = document.getElementById('imageContainer');
            if (!imageContainer) return;

            const items = imageContainer.getElementsByClassName('relative');
            let draggedItem = null;

            Array.from(items).forEach(item => {
                item.setAttribute('draggable', 'true');

                item.addEventListener('dragstart', function(e) {
                    draggedItem = this;
                    e.dataTransfer.effectAllowed = 'move';
                    this.classList.add('opacity-50');
                });

                item.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                });

                item.addEventListener('drop', function(e) {
                    e.preventDefault();
                    if (this !== draggedItem) {
                        const parent = this.parentNode;
                        const allItems = [...parent.children];
                        const draggedIndex = allItems.indexOf(draggedItem);
                        const droppedIndex = allItems.indexOf(this);

                        if (draggedIndex !== droppedIndex) {
                            const placeholder = document.createElement('div');
                            parent.replaceChild(placeholder, draggedItem);
                            parent.replaceChild(draggedItem, this);
                            parent.replaceChild(this, placeholder);
                        }

                        updatePositions();
                    }
                });

                item.addEventListener('dragend', function() {
                    this.classList.remove('opacity-50');
                    draggedItem = null;
                });
            });
        }

        function updatePositions() {
            const imageContainer = document.getElementById('imageContainer');
            const items = imageContainer.getElementsByClassName('relative');

            const orderData = Array.from(items).map((item, index) => {
                const imageInput = item.querySelector('input[type="file"]');
                return {
                    id: imageInput.files.length > 0 ? `new_${index}` : '',
                    position: index,
                    isNew: imageInput.files.length > 0
                };
            }).filter(item => item.id !== '');

            document.getElementById('imageOrder').value = JSON.stringify(orderData);
        }

        // 在 DOMContentLoaded 事件中初始化拖曳功能
        document.addEventListener('DOMContentLoaded', function() {
            initializeDragAndDrop();
            updatePositions();
        });
    </script>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
</x-template-user-layout>

<meta name="csrf-token" content="{{ csrf_token() }}">
