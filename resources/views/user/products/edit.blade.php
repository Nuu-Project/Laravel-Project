@php
    use App\Enums\Tagtype;
@endphp

<x-template-user-layout>
    <script src="{{ asset('js/product-editor.js') }}"></script>

    <x-flex-container>
        <x-div.container>
            <div class="grid gap-6 md:gap-8">
                <x-div.grid>
                    <h1 class="text-2xl sm:text-3xl font-bold">修改刊登商品</h1>
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
                            <option value="" disabled @if (empty($product->semester)) selected @endif>選擇學期...
                            </option>
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
                            <option value="" disabled @if (empty($product->subject)) selected @endif>選擇科目...
                            </option>
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
                            <option value="" disabled @if (empty($product->category)) selected @endif>選擇課程類別...
                            </option>
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
                                <div class="relative h-[192px]" x-data="imageUploader{{ $i }}">
                                    <input type="file" name="images[]" id="image{{ $i }}" class="hidden"
                                        accept="image/*" @change="startUpload($event)">
                                    <input type="hidden" name="image_ids[]"
                                        value="{{ $product->getMedia('images')->sortBy('order_column')->values()->get($i)?->id ?? '' }}">
                                    <label for="image{{ $i }}"
                                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div id="placeholder{{ $i }}"
                                            class="flex flex-col items-center justify-center pt-5 pb-6"
                                            x-show="!uploading || error"
                                            :class="{ 'hidden': hasExistingImage && !error }">
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
                                            class="absolute inset-0 flex items-center justify-center"
                                            :class="{ 'hidden': !hasExistingImage && !success }">
                                            <img src="{{ $product->getMedia('images')->sortBy('order_column')->values()->get($i)?->getUrl() ?? '#' }}"
                                                alt="圖片預覽" class="max-w-full max-h-full object-contain">
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
                                        id="deleteButton{{ $i }}" x-show="hasExistingImage || success"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform scale-75"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        @click="removeImage({{ $product->id }}, '{{ $product->getMedia('images')->sortBy('order_column')->values()->get($i)?->id ?? 'null' }}')">
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

                    <x-button.submit>
                        儲存修改
                    </x-button.submit>
                </form>
            </div>
        </x-div.container>
    </x-flex-container>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
</x-template-user-layout>
