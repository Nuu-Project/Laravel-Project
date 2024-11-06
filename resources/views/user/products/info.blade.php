<!DOCTYPE html>
<html lang="en">

<head>
    <x-head />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-body">

    <x-navbar-home />


    <div class="grid md:grid-cols-2 gap-6 lg:gap-12 items-start max-w-6xl px-4 mx-auto py-6">
        <div>
            {{-- 主圖片顯示區域 --}}
            @if($product->media->isNotEmpty())
                <div class="relative mb-4">
                    @foreach($product->getMedia('images') as $index => $media)
                        <img 
                            src="{{ $media->getUrl() }}" 
                            alt="Product Image {{ $index + 1 }}" 
                            width="1200" 
                            height="900" 
                            style="aspect-ratio: 900 / 1200; object-fit: cover;" 
                            class="w-full rounded-md object-cover {{ $index === 0 ? '' : 'hidden' }}" 
                            data-index="{{ $index }}"
                        />
                    @endforeach
                    
                    {{-- 左右箭頭 --}}
                    <button id="leftArrow" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-r hover:bg-opacity-70 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button id="rightArrow" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-l hover:bg-opacity-70 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            @else
                <div class="text-center p-4 bg-gray-100 rounded-md">沒有圖片</div>
            @endif

            {{-- 縮略圖區域 --}}
            <div class="flex justify-start mt-4 space-x-4 overflow-x-auto pb-2">
                @foreach($product->getMedia('images') as $index => $media)
                    <div class="w-24 h-24 border-2 border-gray-300 flex items-center justify-center cursor-pointer thumbnail hover:border-blue-400 transition-all rounded-md" 
                         data-index="{{ $index }}">
                        <img src="{{ $media->getUrl() }}" 
                             alt="Thumbnail {{ $index + 1 }}" 
                             class="max-w-full max-h-full object-cover rounded-sm"
                        />
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 商品信息 --}}
        <div class="grid gap-4 md:gap-10 items-start">
            <!-- 商品名稱和檢舉按鈕 -->
            <div class="flex items-start justify-between">
                <div class="flex-grow pr-4">
                    <h1 class="text-3xl font-bold break-words">商品名稱：{{ $product->name }}</h1>
                    <div class="mt-2">
                        <h2 class="font-semibold text-xl">用戶名稱：{{ $product->user->name }}</h2>
                    </div>
                </div>
                <button id="reportButton" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded flex-shrink-0">
                    檢舉
                </button>
            </div>
            <!-- 其他商品信息 -->
            <div class="space-y-4">
                <p class="text-2xl font-bold">${{ number_format($product->price) }}</p>
                <p class="text-gray-600">上架時間：{{ $product->created_at->format('Y-m-d H:i:s') }}</p>
                <p class="text-muted-foreground text-2xl">商品介紹：{{ $product->description }}</p>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentIndex = 0;
        const images = document.querySelectorAll('img[data-index]');
        const thumbnails = document.querySelectorAll('.thumbnail');
        const totalImages = images.length;

        function updateDisplay(newIndex) {
            images[currentIndex].classList.add('hidden');
            thumbnails[currentIndex].classList.remove('border-blue-500');
            thumbnails[currentIndex].classList.add('border-gray-300');

            currentIndex = (newIndex + totalImages) % totalImages;

            images[currentIndex].classList.remove('hidden');
            thumbnails[currentIndex].classList.remove('border-gray-300');
            thumbnails[currentIndex].classList.add('border-blue-500');
        }

        function changeImage(direction) {
            let newIndex = (currentIndex + direction + totalImages) % totalImages;
            updateDisplay(newIndex);
        }

        // 為縮略圖添加點擊事件
        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => updateDisplay(index));
        });

        // 為左右箭頭添加點擊事件
        document.getElementById('leftArrow').addEventListener('click', () => changeImage(-1));
        document.getElementById('rightArrow').addEventListener('click', () => changeImage(1));

        // 初始化第一張圖片的縮略圖邊框
        updateDisplay(0);
    });

    window.addEventListener('load', function() {
        console.log('頁面已完全加載');
        var reportButton = document.getElementById('reportButton');
        if (reportButton) {
            console.log('找到檢舉按鈕');
            reportButton.addEventListener('click', function() {
                console.log('檢舉按鈕被點擊');
                var inputOptions = @json($reports->toArray());
                try {
                    Swal.fire({
                        title: '檢舉',
                        input: 'select',
                        inputOptions: inputOptions,
                        inputPlaceholder: '選擇檢舉原因',
                        showCancelButton: true,
                        confirmButtonText: '送出檢舉',
                        cancelButtonText: '取消',
                        inputValidator: (value) => {
                            if (!value) {
                                return '請選擇一個選項'
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var reportId = result.value;
                            var description = '描述信息';  // 替換為實際的描述信息

                            $.ajax({
                                url: '{{ route("reports.store") }}', // 使用 Blade 生成的路由
                                method: 'POST',
                                data: {
                                    report_id: reportId,
                                    description: description,
                                    _token: '{{ csrf_token() }}', // 確保 CSRF 保護
                                    product: '{{ $product->id }}',
                                },
                                success: function(response) {
                                    if (response.message === '你已檢舉過了') {
                                        Swal.fire({
                                            title: `${response.message}`, 
                                            html: `檢舉原因已更新：<br>
                                                  <p style="white-space: pre-wrap;">${response.description}</p>`, 
                                            icon: 'info'
                                        });
                                    } else {
                                        Swal.fire('檢舉已送出', response.message, 'success');
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire('錯誤', '無法提交檢舉', 'error');
                                }
                            });
                        }
                    });
                } catch (error) {
                    console.error('SweetAlert2 錯誤:', error);
                }
            });
        } else {
            console.error('未找到檢舉按鈕');
        }
    });
    </script>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('products.chirps.store', ['product' => $product->id]) }}" 
              class="space-y-4">
            @csrf
            <textarea name="message"
                      placeholder="{{ __('今天想留點什麼足跡?') }}"
                      class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm min-h-[100px]"
            >{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->store->get('message')" class="mt-2" />
            <x-primary-button>{{ __('留言') }}</x-primary-button>
        </form>

        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            @foreach ($chirps as $chirp)
                <div class="p-6 flex space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-gray-800">{{ $chirp->user->name }}</span>
                                <small class="ml-2 text-sm text-gray-600">{{ $chirp->created_at->format('Y/m/d , H:i:s') }}</small>
                                @unless ($chirp->created_at->eq($chirp->updated_at))
                                    <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                                @endunless
                            </div>
                            @if ($chirp->user->is(auth()->user()))
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                            <x-dropdown-link :href="route('products.chirps.edit', ['product' => $product->id, 'chirp' => $chirp->id])">
                            {{ __('更改') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('products.chirps.destroy', ['product' => $product->id, 'chirp' => $chirp->id]) }}">
                            @csrf
                            @method('delete')
                            <x-dropdown-link :href="route('products.chirps.destroy', ['product' => $product->id, 'chirp' => $chirp->id])" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('刪除') }}
                            </x-dropdown-link>
                            </form>
                            </x-slot>
                            </x-dropdown>
                            @endif
                        </div>
                        <p class="mt-4 text-lg text-gray-900">{{ $chirp->message }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
