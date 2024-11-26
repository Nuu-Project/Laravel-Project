<x-head-layout>

    <div class="flex flex-col md:flex-row h-screen bg-gray-100">
        <x-user-link />

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
                            <div class="grid gap-2">
                                <label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    for="name">
                                    書名
                                </label>
                                <input
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    id="name" name="name" placeholder="請輸入書名" value="{{ $product->name }}" />
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
                                    value="{{ $product->price }}" />
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
                                            <option value="{{ $tag->getTranslation('slug', 'zh') }}"
                                                @if ($gradeTag && $tag->getTranslation('slug', 'zh') == $gradeTag->getTranslation('slug', 'zh')) selected @endif>
                                                {{ $tag->getTranslation('name', 'zh') }}
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
                                            <option value="{{ $tag->getTranslation('slug', 'zh') }}"
                                                @if ($semesterTag && $tag->getTranslation('slug', 'zh') == $semesterTag->getTranslation('slug', 'zh')) selected @endif>
                                                {{ $tag->getTranslation('name', 'zh') }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                            </div>

                            <div class="grid gap-2">
                                <label class="text-sm font-medium leading-none" for="category">課程類別</label>
                                <select id="category" name="category"
                                    class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option selected>選擇課程類別...</option>
                                    @foreach ($tags as $tag)
                                        @if ($tag->type === '課程')
                                            <option value="{{ $tag->getTranslation('slug', 'zh') }}"
                                                @if ($categoryTag && $tag->getTranslation('slug', 'zh') == $categoryTag->getTranslation('slug', 'zh')) selected @endif>
                                                {{ $tag->getTranslation('name', 'zh') }}
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

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
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
                                                        800x400px)</p>
                                                </div>
                                                <div id="preview{{ $i }}"
                                                    class="absolute inset-0 flex items-center justify-center {{ $product->getMedia('images')->sortBy('order_column')->values()->get($i) ? '' : 'hidden' }}">
                                                    <img src="{{ $product->getMedia('images')->sortBy('order_column')->values()->get($i)?->getUrl() ?? '#' }}"
                                                        alt="預覽圖片" class="max-w-full max-h-full object-contain">
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
                                    const file = input.files[0];
                                    const reader = new FileReader();

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
                                    if (imageId === null) {
                                        // 如果是新上傳的圖片，直接從 UI 中移除
                                        removeImage(index);
                                    } else {
                                        // 如果是已存在的圖片，發送 AJAX 請求刪除
                                        fetch(`/user/products/${productId}/images/${imageId}`, {
                                                method: 'DELETE',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Content-Type': 'application/json',
                                                    'Accept': 'application/json',
                                                },
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    removeImage(index);
                                                } else {
                                                    console.error('刪除圖片失敗：' + data.message);
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                            });
                                    }
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

                                    // 清除隱藏的 image_id 輸入
                                    const imageIdInput = document.querySelector(`input[name="image_ids[]"]:nth-of-type(${index + 1})`);
                                    if (imageIdInput) {
                                        imageIdInput.value = '';
                                    }
                                }
                            </script>
                            <button
                                class="inline-flex items-center justify-center whitespace-nowrap rounded-xl text-base sm:text-lg font-semibold ring-offset-background transition-colors ease-in-out duration-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-blue-500 text-white hover:bg-blue-700 h-10 sm:h-11 px-4 sm:px-8"
                                type="submit">
                                儲存修改
                            </button>
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
</x-head-layout>

