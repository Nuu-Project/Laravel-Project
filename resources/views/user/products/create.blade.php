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
                            <div class="grid gap-2">
                                <label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    for="name">
                                    書名
                                </label>
                                <input
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    id="name" name="name" placeholder="請輸入書名" maxlength="50" 
                                    />
                                    
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
                                    id="price" name="price" placeholder="輸入價格" type="number" />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                            <div class="grid gap-2">
                                <label class="text-sm font-medium leading-none" for="grade">年級</label>
                                <select id="grade" name="grade"
                                    class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option value="">選擇適用的年級...</option>
                                    @foreach ($tags as $tag)
                                        @if ($tag->type === '年級')
                                            <option value="{{ $tag->getTranslation('slug', 'zh') }}">
                                                {{ $tag->getTranslation('name', 'zh') }}</option>
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
                                        @if ($tag->type === '學期')
                                            <option value="{{ $tag->getTranslation('slug', 'zh') }}">
                                                {{ $tag->getTranslation('name', 'zh') }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                            </div>
                            <div class="grid gap-2">
                                <label class="text-sm font-medium leading-none" for="category">課程類別</label>
                                <select id="category" name="category"
                                    class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option value="">選擇課程類別...</option>
                                    @foreach ($tags as $tag)
                                        @if ($tag->type === '課程')
                                            <option value="{{ $tag->getTranslation('slug', 'zh') }}">
                                                {{ $tag->getTranslation('name', 'zh') }}</option>
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
                                    id="description" name="description" placeholder="請填寫有關該書的書況or使用情況等等~~" rows="4"></textarea>
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
                                            <label for="image{{ $i }}"
                                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden">
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
                                                            class="font-semibold">點擊上傳</span> 或拖放</p>
                                                    <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (最大.
                                                        3200x3200px)</p>
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
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endfor
                                </div>
                                <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            </div>
                            <button
                                class="inline-flex items-center justify-center whitespace-nowrap rounded-xl text-base sm:text-lg font-semibold ring-offset-background transition-colors ease-in-out duration-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-blue-500 text-white hover:bg-blue-700 h-10 sm:h-11 px-4 sm:px-8"
                                type="submit">
                                刊登商品
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function previewImage(input, number) {
            const preview = document.getElementById('preview' + number);
            const placeholder = document.getElementById('placeholder' + number);
            const deleteButton = document.getElementById('deleteButton' + number);
            const file = input.files[0];
            const reader = new FileReader();

            reader.onloadend = function() {
                preview.querySelector('img').src = reader.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                deleteButton.classList.remove('hidden');

                // 重新初始化拖曳功能
                initializeDragAndDrop();
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

        function removeImage(number) {
            const input = document.getElementById('image' + number);
            const preview = document.getElementById('preview' + number);
            const placeholder = document.getElementById('placeholder' + number);
            const deleteButton = document.getElementById('deleteButton' + number);

            input.value = '';
            preview.querySelector('img').src = '#';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            deleteButton.classList.add('hidden');
        }

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

                        if (draggedIndex < droppedIndex) {
                            this.parentNode.insertBefore(draggedItem, this.nextSibling);
                        } else {
                            this.parentNode.insertBefore(draggedItem, this);
                        }
                    }
                });

                item.addEventListener('dragend', function() {
                    this.classList.remove('opacity-50');
                    draggedItem = null;
                });
            });
        }

        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();

            // 檢查必填欄位
            const requiredFields = ['name', 'price', 'description', 'grade', 'semester', 'category'];
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

                if (imageInput.files.length > 0) {
                    hasValidImage = true;
                    break;
                }
            }

            if (!allFieldsFilled || !hasValidImage) {
                alert('請確保所有必填欄位都已填寫，且至少上傳一張商品圖片');
                return;
            }

            // 提交表單
            this.submit();
        });

        // 在頁面加載時初始化拖曳功能
        document.addEventListener('DOMContentLoaded', function() {
            initializeDragAndDrop();
        });
    </script>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
</x-template-layout>
