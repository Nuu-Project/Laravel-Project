<x-template-layout>

    <div class="flex flex-col md:flex-row h-screen bg-gray-100">
        <x-link-user />

        <!-- 主要內容區 -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <x-navbar-user />

            <!-- 主要內容 -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="grid gap-6 md:gap-8">
                        <div class="grid gap-2">
                            <h1 class="text-2xl sm:text-3xl font-bold">修改刊登商品</h1>
                            <p class="text-sm sm:text-base text-muted-foreground">請依照下順序進行填寫，照片上傳張數最多五張。</p>
                            <p class="text-sm sm:text-base text-muted-foreground">圖片最左邊將會是商品首圖。</p>
                        </div>

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

                        <form class="grid gap-6"
                            action="{{ route('user.products.update', ['product' => $product->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="imageOrder" id="imageOrder">
                            <input type="hidden" name="deleted_image_ids" id="deletedImageIds" value="[]">
                            <div class="grid gap-2">
                                <label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    for="name">
                                    書名
                                </label>
                                <input
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    id="name" name="name" placeholder="請輸入書名" value="{{ $product->name }}"
                                    maxlength="50" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="grid gap-2">
                                <label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    for="price">
                                    價格
                                </label>
                                <input
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    id="price" name="price" placeholder="輸入價格" type="number"
                                    value="{{ $product->price }}" disabled readonly />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <div class="grid gap-2">
                                <label class="text-sm font-medium leading-none" for="grade">年級</label>
                                <select id="grade" name="grade"
                                    class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option value="" disabled @if (empty($product->grade)) selected @endif>
                                        選擇適用的年級...</option>

                                    @foreach ($tags as $tag)
                                        @if ($tag->type === '年級')
                                            <option value="{{ $tag->id }}"
                                                @if ($gradeTag && $tag->id == $gradeTag->id) selected @endif>
                                                {{ $tag->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                            </div>

                            <div class="grid gap-2">
                                <label class="text-sm font-medium leading-none" for="semester">學期</label>
                                <select id="semester" name="semester"
                                    class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option selected>選擇學期...</option>
                                    @foreach ($tags as $tag)
                                        @if ($tag->type === '學期')
                                            <option value="{{ $tag->id }}"
                                                @if ($semesterTag && $tag->id == $semesterTag->id) selected @endif>
                                                {{ $tag->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                            </div>

                            <div class="grid gap-2">
                                <label class="text-sm font-medium leading-none" for="subject">科目</label>
                                <select id="subject" name="subject"
                                    class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option selected>選擇科目...</option>
                                    @foreach ($tags as $tag)
                                        @if ($tag->type === '科目')
                                            <option value="{{ $tag->id }}"
                                                @if ($subjectTag && $tag->id == $subjectTag->id) selected @endif>
                                                {{ $tag->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                            </div>

                            <div class="grid gap-2">
                                <label class="text-sm font-medium leading-none" for="category">課程類別</label>
                                <select id="category" name="category"
                                    class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option selected>選擇課程類別...</option>
                                    @foreach ($tags as $tag)
                                        @if ($tag->type === '課程')
                                            <option value="{{ $tag->id }}"
                                                @if ($categoryTag && $tag->id == $categoryTag->id) selected @endif>
                                                {{ $tag->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>

                            <div class="grid gap-2">
                                <label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    for="description">
                                    商品介紹
                                </label>
                                <textarea
                                    class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    id="description" name="description" placeholder="請填寫有關該書的書況or使用情況等等~~" rows="4">{{ $product->description }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                            <div class="grid gap-2">
                                <label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    for="image">
                                    上傳圖片
                                </label>

                                <div id="imageContainer"
                                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                    @for ($i = 0; $i < 5; $i++)
                                        <div class="relative">
                                            <input type="file" name="images[]" id="image{{ $i }}"
                                                class="hidden" accept="image/*"
                                                onchange="previewImage(this, {{ $i }})">
                                            <input type="hidden" name="image_ids[]"
                                                value="{{ $product->getMedia('images')->sortBy('order_column')->values()->get($i)?->id ?? '' }}">
                                            <label for="image{{ $i }}"
                                                class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                                <div id="placeholder{{ $i }}"
                                                    class="flex flex-col items-center justify-center pt-5 pb-6 {{ $product->getMedia('images')->sortBy('order_column')->values()->get($i) ? 'hidden' : '' }}">
                                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 20 16">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                    </svg>
                                                    <p class="mb-2 text-sm text-gray-500"><span
                                                            class="font-semibold">點擊上傳</span> 或拖放</p>
                                                    <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (最大.
                                                        3200x3200px)</p>
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
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endfor
                                </div>
                                <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            </div>

                            <script>
                                function previewImage(input, number) {
                                    const preview = document.getElementById('preview' + number);
                                    const placeholder = document.getElementById('placeholder' + number);
                                    const deleteButton = document.getElementById('deleteButton' + number);
                                    const imageIdInput = input.parentNode.querySelector('input[name="image_ids[]"]');
                                    const file = input.files[0];
                                    const reader = new FileReader();

                                    // 只有當有新文件被選擇，且原來有圖片ID時，才標記原圖為刪除
                                    if (file && imageIdInput.value) {
                                        const deletedImageIds = JSON.parse(document.getElementById('deletedImageIds').value);
                                        if (!deletedImageIds.includes(imageIdInput.value)) {
                                            deletedImageIds.push(imageIdInput.value);
                                            document.getElementById('deletedImageIds').value = JSON.stringify(deletedImageIds);
                                        }
                                        // 清除原來的圖片ID，因為我們現在有了新圖片
                                        imageIdInput.value = '';
                                    }

                                    reader.onloadend = function() {
                                        preview.querySelector('img').src = reader.result;
                                        preview.classList.remove('hidden');
                                        placeholder.classList.add('hidden');
                                        deleteButton.classList.remove('hidden');
                                    }

                                    if (file) {
                                        reader.readAsDataURL(file);
                                    } else {
                                        preview.querySelector('img').src = '#';
                                        preview.classList.add('hidden');
                                        placeholder.classList.remove('hidden');
                                        deleteButton.classList.add('hidden');
                                    }
                                }

                                // 頁面加載時初始化預覽
                                document.addEventListener('DOMContentLoaded', function() {
                                    @for ($i = 0; $i < 5; $i++)
                                        const preview{{ $i }} = document.getElementById('preview{{ $i }}');
                                        const placeholder{{ $i }} = document.getElementById('placeholder{{ $i }}');
                                        const deleteButton{{ $i }} = document.getElementById(
                                            'deleteButton{{ $i }}');
                                        if (preview{{ $i }}.querySelector('img')?.src && preview{{ $i }}
                                            .querySelector('img').src !== window.location.href + '#') {
                                            preview{{ $i }}.classList.remove('hidden');
                                            placeholder{{ $i }}.classList.add('hidden');
                                            deleteButton{{ $i }}.classList.remove('hidden');
                                        }
                                    @endfor
                                });

                                function deleteImage(productId, imageId, index) {
                                    // 標記圖片為已刪除
                                    const deletedImageIds = JSON.parse(document.getElementById('deletedImageIds').value);
                                    if (imageId && !deletedImageIds.includes(imageId)) {
                                        deletedImageIds.push(imageId);
                                        document.getElementById('deletedImageIds').value = JSON.stringify(deletedImageIds);
                                    }

                                    // 更新 UI
                                    removeImage(index);
                                    updatePositions();
                                }

                                function removeImage(index) {
                                    const preview = document.getElementById(`preview${index}`);
                                    const placeholder = document.getElementById(`placeholder${index}`);
                                    const imageInput = document.getElementById(`image${index}`);
                                    const deleteButton = document.getElementById(`deleteButton${index}`);

                                    // 重置預覽圖
                                    preview.querySelector('img').src = '#';
                                    preview.classList.add('hidden');

                                    // 顯示佔位符
                                    placeholder.classList.remove('hidden');

                                    // 清空文件輸入
                                    imageInput.value = '';

                                    // 隱藏刪除按鈕
                                    deleteButton.classList.add('hidden');
                                }
                                // 修改表單提交處理邏輯
                                document.querySelector('form').addEventListener('submit', function(event) {
                                    event.preventDefault();

                                    // 檢查必填欄位
                                    const requiredFields = ['name', 'description', 'grade', 'semester', 'category'];
                                    let allFieldsFilled = true;

                                    for (const fieldId of requiredFields) {
                                        const field = document.getElementById(fieldId);
                                        if (!field.value) {
                                            allFieldsFilled = false;
                                            break;
                                        }
                                    }

                                    // 檢查圖片
                                    let hasValidImage = false;
                                    const imageContainers = document.querySelectorAll('#imageContainer .relative');

                                    for (const container of imageContainers) {
                                        const preview = container.querySelector('[id^="preview"]');
                                        const imageInput = container.querySelector('input[type="file"]');
                                        const imageIdInput = container.querySelector('input[name="image_ids[]"]');

                                        if ((imageInput.files.length > 0) ||
                                            (imageIdInput.value &&
                                                !preview.classList.contains('hidden') &&
                                                preview.querySelector('img')?.src &&
                                                preview.querySelector('img').src !== '#')) {
                                            hasValidImage = true;
                                            break;
                                        }
                                    }

                                    if (!allFieldsFilled || !hasValidImage) {
                                        alert('請確保所有必填欄位都已填寫，且至少上傳一張商品圖片');
                                        return;
                                    }

                                    // 更新圖片順序
                                    updatePositions();

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
                            </script>
                            <x-button-create-edit>
                                儲存修改
                            </x-button-create-edit>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- 圖片預覽 -->

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
</x-template-layout>
