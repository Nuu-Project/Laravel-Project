@props([
    'index',
    'productId',
    'imageId' => null,
    'imageUrl' => null,
    'maxSize' => '2MB',
    'maxDimensions' => '3200x3200px'
])

<div class="relative h-[192px]" x-data="imageUploader{{ $index }}">
    <input type="file" name="images[]" id="image{{ $index }}" class="hidden" accept="image/*"
        @change="startUpload($event)">
    <input type="hidden" name="image_ids[]" value="{{ $imageId ?? '' }}">
    <label for="image{{ $index }}"
        class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
        <div id="placeholder{{ $index }}"
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
                        {{ $maxDimensions }}, {{ $maxSize }})</p>
                </div>
            </template>
            <template x-if="error">
                <p class="mt-2 text-xs text-red-500" x-text="errorMessage"></p>
            </template>
        </div>
        <div id="preview{{ $index }}"
            class="absolute inset-0 flex items-center justify-center"
            :class="{ 'hidden': !hasExistingImage && !success }">
            <img src="{{ $imageUrl ?? '#' }}" alt="圖片預覽"
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
        id="deleteButton{{ $index }}" x-show="hasExistingImage || success"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-75"
        x-transition:enter-end="opacity-100 transform scale-100"
        @click="removeImage({{ $productId }}, '{{ $imageId ?? 'null' }}')">
        <svg class="w-4 h-4" fill="none" stroke="currentColor"
            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>
