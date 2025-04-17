@php
    use App\Enums\Tagtype;
@endphp

<x-template-user-layout>
    <script src="{{ asset('js/product-uploader.js') }}"></script>

    <x-flex-container>
        <x-div.container>
            <div class="grid gap-6 md:gap-8">
                <x-div.grid>
                    <h1 class="text-2xl sm:text-3xl font-bold">新增刊登商品</h1>
                    <p class="text-sm sm:text-base text-muted-foreground">請依照下順序進行填寫，照片上傳張數最多五張。</p>
                    <p class="text-sm sm:text-base text-muted-foreground">圖片最左邊將會是商品首圖。</p>
                </x-div.grid>

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
                        <x-label.form for="name">
                            書名
                        </x-label.form>
                        <x-input.tags id="name" name="name" placeholder="請輸入書名" maxlength="50"
                            value="{{ old('name') }}" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <x-label.form for="price">
                            價格 (不可重複修改)
                        </x-label.form>
                        <x-input.tags id="price" name="price" placeholder="輸入價格" type="number"
                            value="{{ old('price') }}" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <label class="text-sm font-medium leading-none" for="grade">年級</label>
                        <x-select.form id="grade" name="grade">
                            <option value="">選擇適用的年級...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Grade->value)
                                    <option value="{{ $tag->id }}"
                                        {{ old('grade') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}</option>
                                @endif
                            @endforeach
                        </x-select.form>
                        <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <label class="text-sm font-medium leading-none" for="semester">學期</label>
                        <x-select.form id="semester" name="semester">
                            <option value="">選擇學期...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Semester->value)
                                    <option value="{{ $tag->id }}"
                                        {{ old('semester') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}</option>
                                @endif
                            @endforeach
                        </x-select.form>
                        <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                    </x-div.grid>

                    <x-div.grid>
                        <label class="text-sm font-medium leading-none" for="subject">科目</label>
                        <x-input.tags 
                            id="subject" 
                            name="subject" 
                            type="text"
                            placeholder="選擇科目..." 
                            value="{{ old('subject') }}"
                        />
                        <div id="searchResults" class="mt-2 bg-white border rounded-md shadow-lg hidden">
                            <!-- 搜尋結果會動態顯示在這裡 -->
                        </div>
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>

                        <label class="text-sm font-medium leading-none" for="category">課程類別</label>
                        <x-select.form id="category" name="category">
                            <option value="">選擇課程類別...</option>
                            @foreach ($tags as $tag)
                                @if ($tag->type === Tagtype::Category->value)
                                    <option value="{{ $tag->id }}"
                                        {{ old('category') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}</option>
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
                            id="description" name="description" placeholder="請填寫有關該書的書況or使用情況等等~~" rows="4" maxlength = "50">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </x-div.grid>
                    <x-div.grid>
                        <x-label.form for="image">
                            上傳圖片
                        </x-label.form>
                        <div id="imageContainer"
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @for ($i = 0; $i < 5; $i++)
                                <div class="relative h-[192px]" x-data="imageUploader{{ $i }}">
                                    <input type="file" id="image{{ $i }}" class="hidden" accept="image/*"
                                        @change="startUpload($event)">
                                    <label for="image{{ $i }}"
                                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div id="placeholder{{ $i }}"
                                            class="flex flex-col items-center justify-center pt-5 pb-6"
                                            x-show="!uploading || error">
                                            <template x-if="!processing">
                                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                </svg>
                                            </template>
                                            <template x-if="processing">
                                                <div class="text-center text-sm font-medium text-blue-600">處理中...</div>
                                            </template>
                                            <template x-if="!processing">
                                                <div class="text-center">
                                                    <p class="mb-2 text-sm text-gray-500"><span
                                                            class="font-semibold">點擊上傳</span>或拖曳</p>
                                                    <p class="text-xs text-gray-500">PNG,JPG,JPEG,GIF (最大.
                                                        3200x3200px, 2MB)</p>
                                                </div>
                                            </template>
                                            <template x-if="error">
                                                <p class="mt-2 text-xs text-red-500" x-text="errorMessage"></p>
                                            </template>
                                        </div>
                                        <div id="preview{{ $i }}"
                                            class="absolute inset-0 flex items-center justify-center hidden">
                                            <img src="#" alt="預覽圖片"
                                                class="max-w-full max-h-full object-contain">
                                        </div>
                                    </label>

                                    <div class="absolute bottom-0 left-0 right-0 pb-2">
                                        <div class="mt-2 relative h-2 rounded-full overflow-hidden transition-opacity duration-300"
                                            x-show="uploading && !error"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                            <div class="absolute inset-0 bg-gray-200 rounded-full"></div>
                                            <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-300"
                                                :style="`width: ${progress}%`"></div>
                                        </div>

                                        <div class="text-xs mt-1 font-semibold flex items-center justify-center h-4 transition-opacity duration-300"
                                            x-show="uploading && !error"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                            <span x-text="`${progress}%`" class="mr-1"></span>
                                            <span x-show="progress < 100">上傳中...</span>
                                            <span x-show="progress >= 100 && processing">處理中...</span>
                                            <span x-show="success" class="text-green-500">上傳成功</span>
                                        </div>
                                    </div>

                                    <button type="button"
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 m-1 shadow-md transition-all duration-300 hover:bg-red-600"
                                        id="deleteButton{{ $i }}" x-show="success"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform scale-75"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        @click="removeImage()">
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
                    <x-button.submit>
                        刊登商品
                    </x-button.submit>
                </form>
            </div>
        </x-div.container>
    </x-flex-container>
    </div>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                title: '{{ config('app.name') }} 回應',
                text: '{{ session('success') }}',
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: '查看商品列表',
                cancelButtonText: '繼續刊登',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('user.products.index') }}';
                }
            });
        </script>
    @endif
</x-template-user-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectInput = document.getElementById('subject');
    const searchResults = document.getElementById('searchResults');
    const subjects = @json($tags->where('type', Tagtype::Subject->value)->pluck('name', 'id'));

    subjectInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        if (searchTerm.length < 1) {
            searchResults.classList.add('hidden');
            return;
        }

        // 過濾符合的科目
        const matches = Object.entries(subjects).filter(([id, name]) => 
            name.toLowerCase().includes(searchTerm)
        );

        // 顯示搜尋結果
        if (matches.length > 0) {
            searchResults.innerHTML = matches.map(([id, name]) => `
                <div class="p-2 hover:bg-gray-100 cursor-pointer" 
                     onclick="selectSubject('${id}', '${name}')">
                    ${name}
                </div>
            `).join('');
            searchResults.classList.remove('hidden');
        } else {
            searchResults.innerHTML = '<div class="p-2 text-gray-500">找不到符合的科目</div>';
            searchResults.classList.remove('hidden');
        }
    });

    // 點擊其他地方時隱藏搜尋結果
    document.addEventListener('click', function(e) {
        if (!subjectInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });
});

function selectSubject(id, name) {
    document.getElementById('subject').value = name;
    document.getElementById('searchResults').classList.add('hidden');
}
</script>

<style>
#searchResults {
    position: absolute;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
}

#searchResults div {
    transition: background-color 0.2s;
}
</style>
