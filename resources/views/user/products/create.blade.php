@php
    use App\Enums\Tagtype;
@endphp

<x-template-user-layout>

    <!-- 主要內容 -->
    <x-main.flex-container>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid gap-6 md:gap-8">
                <div class="grid gap-2">
                    <h1 class="text-2xl sm:text-3xl font-bold">新增刊登商品</h1>
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

                <form class="grid gap-6" action="{{ route('user.products.store') }}" method="POST"
                    enctype="multipart/form-data" id="productForm">
                    @csrf
                    <input type="hidden" name="imageOrder" id="imageOrder">
                    <div class="grid gap-2">
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
                    </div>
                    <div class="grid gap-2">
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
                    </div>
                    <div class="grid gap-2">
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
                    </div>
                    <div class="grid gap-2">
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
                    </div>
                    <div class="grid gap-2">
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
                    </div>
                    <div class="grid gap-2">
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
                    </div>
                    <div class="grid gap-2">
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="description">
                            商品介紹 (最長50字)
                        </label>
                        <textarea
                            class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            id="description" name="description" placeholder="請填寫有關該書的書況or使用情況等等~~" rows="4" maxlength="50">{{ old('description') }}</textarea>
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
                    </div>
                    <x-button.create-edit>
                        刊登商品
                    </x-button.create-edit>
                </form>
            </div>
        </div>
    </x-main.flex-container>
    </div>
    </div>

    <script>
        // 儲存已選擇的圖片檔案
        let savedFiles = new Array(5).fill(null);

        function previewImage(input, number) {
            const preview = document.getElementById('preview' + number);
            const placeholder = document.getElementById('placeholder' + number);
            const deleteButton = document.getElementById('deleteButton' + number);
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onloadend = function() {
                    preview.querySelector('img').src = reader.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    deleteButton.classList.remove('hidden');

                    // 儲存檔案資訊
                    const savedImages = JSON.parse(sessionStorage.getItem('savedFiles') || '[]');
                    savedImages[number] = {
                        dataUrl: reader.result,
                        name: file.name,
                        type: file.type
                    };
                    sessionStorage.setItem('savedFiles', JSON.stringify(savedImages));
                }
                reader.readAsDataURL(file);
            } else {
                preview.querySelector('img').src = '#';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
                deleteButton.classList.add('hidden');

                // 移除儲存的圖片
                const savedImages = JSON.parse(sessionStorage.getItem('savedFiles') || '[]');
                savedImages[number] = null;
                sessionStorage.setItem('savedFiles', JSON.stringify(savedImages));
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

            // 移除儲存的圖片
            const savedImages = JSON.parse(sessionStorage.getItem('savedFiles') || '[]');
            savedImages[index] = null;
            sessionStorage.setItem('savedFiles', JSON.stringify(savedImages));
        }

        // 將 Base64 轉換為 File 物件
        function dataURLtoFile(dataurl, filename) {
            let arr = dataurl.split(','),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]),
                n = bstr.length,
                u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], filename, {
                type: mime
            });
        }

        // 在頁面載入時恢復已保存的圖片
        document.addEventListener('DOMContentLoaded', function() {
            const savedImages = JSON.parse(sessionStorage.getItem('savedFiles') || '[]');

            if (savedImages.length > 0) {
                savedImages.forEach((imageData, index) => {
                    if (imageData) {
                        const preview = document.getElementById('preview' + index);
                        const placeholder = document.getElementById('placeholder' + index);
                        const deleteButton = document.getElementById('deleteButton' + index);
                        const imageInput = document.getElementById('image' + index);

                        if (preview && placeholder && deleteButton && imageInput) {
                            // 恢復預覽圖片
                            preview.querySelector('img').src = imageData.dataUrl;
                            preview.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                            deleteButton.classList.remove('hidden');

                            // 恢復檔案輸入
                            const file = dataURLtoFile(imageData.dataUrl, imageData.name);
                            const container = new DataTransfer();
                            container.items.add(file);
                            imageInput.files = container.files;
                        }
                    }
                });
            }
        });

        // 表單提交成功後清除暫存
        @if (session('success'))
            sessionStorage.removeItem('savedFiles');
        @endif

        // 監聽表單提交
        document.querySelector('form').addEventListener('submit', function(e) {
            const savedImages = JSON.parse(sessionStorage.getItem('savedFiles') || '[]');
            const hasImages = savedImages.some(img => img !== null);

            // 檢查是否有選擇圖片
            if (!hasImages) {
                e.preventDefault();
                alert('請至少上傳一張商品圖片');
            }
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
