@php
    use App\Enums\Tagtype;
@endphp

<x-template-user-layout>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <div class="grid gap-6 md:gap-8">
                <x-div.grid>
                    <h1 class="text-2xl sm:text-3xl font-bold">修改刊登商品</h1>
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

                <form id="productForm" class="grid gap-6"
                    action="{{ route('user.products.update', ['product' => $product->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="imageOrder" id="imageOrder">
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
                            價格 (不可修改)
                        </x-label.form>
                        <x-input.tags id="price" name="price" placeholder="輸入價格" type="number"
                            value="{{ $product->price }}" readonly />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </x-div.grid>

                    <x-div.grid>
                        <label class="text-sm font-medium leading-none" for="grade">年級</label>
                        <x-select.form id="grade" name="grade">
                            <option value="" disabled @if (empty($product->grade)) selected @endif>
                                選擇適用的年級...</option>

                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Grade->value)
                                    <option value="{{ $tag->id }}"
                                        @if ($gradeTag && $tag->id == $gradeTag->id) selected @endif>
                                        {{ $tag->name }}
                                    </option>
                                @endif
                            @endforeach
                        </x-select.form>
                        <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                    </x-div.grid>

                    <x-div.grid>
                        <label class="text-sm font-medium leading-none" for="semester">學期</label>
                        <x-select.form id="semester" name="semester">
                            <option selected>選擇學期...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Semester->value)
                                    <option value="{{ $tag->id }}"
                                        @if ($semesterTag && $tag->id == $semesterTag->id) selected @endif>
                                        {{ $tag->name }}
                                    </option>
                                @endif
                            @endforeach
                        </x-select.form>
                        <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                    </x-div.grid>

                    <x-div.grid>
                        <label class="text-sm font-medium leading-none" for="subject">科目</label>
                        <x-select.form id="subject" name="subject">
                            <option selected>選擇科目...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Subject->value)
                                    <option value="{{ $tag->id }}"
                                        @if ($subjectTag && $tag->id == $subjectTag->id) selected @endif>
                                        {{ $tag->name }}
                                    </option>
                                @endif
                            @endforeach
                        </x-select.form>
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </x-div.grid>

                    <x-div.grid>
                        <label class="text-sm font-medium leading-none" for="category">課程類別</label>
                        <x-select.form id="category" name="category">
                            <option selected>選擇課程類別...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Category->value)
                                    <option value="{{ $tag->id }}"
                                        @if ($categoryTag && $tag->id == $categoryTag->id) selected @endif>
                                        {{ $tag->name }}
                                    </option>
                                @endif
                            @endforeach
                        </x-select.form>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </x-div.grid>

                    <x-div.grid>
                        <x-label.form for="description">
                            商品介紹 (最長50字)
                        </x-label.form>
                        <textarea
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            id="description" name="description" placeholder="請填寫有關該書的書況or使用情況等等~~" rows="4" maxlength = "50">{{ $product->description }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <x-label.form for="image">
                            上傳圖片
                        </x-label.form>

                        <div id="imageContainer"
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @for ($i = 0; $i < 5; $i++)
                                <div class="relative">
                                    <input type="file" name="images[]" id="image{{ $i }}" class="hidden"
                                        accept="image/*" onchange="previewImage(this, {{ $i }})">
                                    <input type="hidden" name="image_ids[]"
                                        value="{{ $product->getMedia('images')->sortBy('order_column')->values()->get($i)?->id ?? '' }}">
                                    <label for="image{{ $i }}"
                                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div id="placeholder{{ $i }}"
                                            class="flex flex-col items-center justify-center pt-5 pb-6 {{ $product->getMedia('images')->sortBy('order_column')->values()->get($i) ? 'hidden' : '' }}">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span
                                                    class="font-semibold">點擊上傳</span> 或拖曳</p>
                                            <p class="text-xs text-gray-500">PNG,JPG,JPEG(最大.
                                                3200x3200px 2MB)</p>
                                        </div>
                                        <div id="preview{{ $i }}"
                                            class="absolute inset-0 flex items-center justify-center {{ $product->getMedia('images')->sortBy('order_column')->values()->get($i) ? '' : 'hidden' }}">
                                            <img src="{{ $product->getMedia('images')->sortBy('order_column')->values()->get($i)?->getUrl() ?? '#' }}"
                                                alt="檔案過大或格式有誤" class="max-w-full max-h-full object-contain">
                                        </div>
                                    </label>
                                    <button type="button"
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 m-1 {{ $product->getMedia('images')->sortBy('order_column')->values()->get($i) ? '' : 'hidden' }}"
                                        id="deleteButton{{ $i }}"
                                        onclick="deleteImage({{ $product->id }}, {{ $product->getMedia('images')->sortBy('order_column')->values()->get($i)?->id ?? 'null' }}, {{ $i }})">
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

                    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

                    <script>
                        // 全局變量
                        let processedImagePaths = {};

                        async function previewImage(input, number) {
                            const preview = document.getElementById('preview' + number);
                            const placeholder = document.getElementById('placeholder' + number);
                            const deleteButton = document.getElementById('deleteButton' + number);
                            const file = input.files[0];

                            if (file) {
                                try {
                                    placeholder.innerHTML = '<div class="text-center">處理中...</div>';

                                    // 上傳並處理新圖片
                                    const formData = new FormData();
                                    formData.append('image', file);

                                    console.log('準備發送圖片處理請求');

                                    const response = await axios.post('/api/products/process-image', formData, {
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                        }
                                    });

                                    console.log('收到圖片處理響應', response.data);

                                    if (response.data.success) {
                                        // 儲存處理後的圖片路徑
                                        processedImagePaths[number] = response.data.path;

                                        const reader = new FileReader();
                                        reader.onloadend = function() {
                                            preview.querySelector('img').src = reader.result;
                                            preview.classList.remove('hidden');
                                            placeholder.classList.add('hidden');
                                            deleteButton.classList.remove('hidden');
                                        }
                                        reader.readAsDataURL(file);
                                    } else {
                                        console.error('圖片處理失敗:', response.data);
                                        removeImage(number);
                                    }
                                } catch (error) {
                                    console.error('上傳過程出錯:', error);
                                    removeImage(number);
                                }
                            } else {
                                removeImage(number);
                            }
                        }

                        function removeImage(number) {
                            const preview = document.getElementById(`preview${number}`);
                            const placeholder = document.getElementById(`placeholder${number}`);
                            const imageInput = document.getElementById(`image${number}`);
                            const deleteButton = document.getElementById(`deleteButton${number}`);

                            delete processedImagePaths[number];

                            // 重置預覽圖片並隱藏整個預覽區域
                            const previewImg = preview.querySelector('img');
                            previewImg.removeAttribute('src'); // 移除 src 屬性而不是設置為 '#'
                            preview.classList.add('hidden');

                            // 顯示原本的 placeholder
                            placeholder.classList.remove('hidden');

                            // 重置文件輸入
                            imageInput.value = '';
                            deleteButton.classList.add('hidden');
                        }

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

                                        // 直接交換兩個元素的位置
                                        if (draggedIndex !== droppedIndex) {
                                            // 創建一個臨時的佔位元素
                                            const placeholder = document.createElement('div');

                                            // 交換元素位置
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
                                const imageIdInput = item.querySelector('input[name="image_ids[]"]');
                                const imageInput = item.querySelector('input[type="file"]');

                                return {
                                    id: imageIdInput.value || (imageInput.files.length > 0 ? `new_${index}` : ''),
                                    position: index,
                                    isNew: imageInput.files.length > 0
                                };
                            }).filter(item => item.id !== '');

                            document.getElementById('imageOrder').value = JSON.stringify(orderData);
                        }

                        // 頁面載入時初始化
                        document.addEventListener('DOMContentLoaded', function() {
                            initializeDragAndDrop();
                            updatePositions();
                        });

                        // 修改表單提交監聽器
                        document.getElementById('productForm').addEventListener('submit', function(e) {
                            e.preventDefault();
                            console.log('Form submitted'); // 調試用

                            // 過濾掉空值，獲取有效的圖片路徑
                            const validPaths = Object.values(processedImagePaths).filter(path => path);
                            console.log('Valid paths:', validPaths); // 調試用

                            // 檢查是否有圖片
                            const hasExistingImages = Array.from(document.querySelectorAll('input[name="image_ids[]"]'))
                                .some(input => input.value && !JSON.parse(document.getElementById('deletedImageIds').value)
                                    .includes(input.value));

                            if (!hasExistingImages && validPaths.length === 0) {
                                alert('請至少上傳一張商品圖片');
                                return;
                            }

                            // 移除任何現有的 encrypted_image_path 輸入
                            this.querySelectorAll('input[name="encrypted_image_path[]"]').forEach(input => input.remove());

                            // 為每個處理過的圖片創建隱藏的輸入欄位
                            validPaths.forEach(path => {
                                console.log('Adding path:', path); // 調試用
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'encrypted_image_path[]';
                                input.value = path;
                                this.appendChild(input);
                            });

                            // 更新圖片順序
                            updatePositions();
                            // 完全移除所有 file input 元素，這樣它們就不會被包含在表單提交中
                            const fileInputs = Array.from(this.querySelectorAll('input[type="file"]'));
                            fileInputs.forEach(input => {
                                // 保存父元素引用
                                const parent = input.parentNode;
                                // 從 DOM 中移除元素
                                parent.removeChild(input);
                            });

                            // 提交表單
                            this.submit();
                        });

                        function deleteImage(productId, imageId, number) {
                            // 如果是已存在的圖片
                            if (imageId) {
                                // 更新要刪除的圖片ID列表
                                const deletedImageIds = JSON.parse(document.getElementById('deletedImageIds').value || '[]');
                                if (!deletedImageIds.includes(imageId)) {
                                    deletedImageIds.push(imageId);
                                    document.getElementById('deletedImageIds').value = JSON.stringify(deletedImageIds);
                                }
                            }

                            // 清除該位置的圖片
                            removeImage(number);
                        }
                    </script>
                    <x-button.create-edit>
                        儲存修改
                    </x-button.create-edit>
                </form>
            </div>
        </x-div.container>
    </x-flex-container>
    </div>
    </div>

    <!-- 圖片預覽 -->

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
</x-template-user-layout>
