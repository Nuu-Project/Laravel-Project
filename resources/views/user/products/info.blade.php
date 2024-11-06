<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <title>聯大二手書交易平台</title>
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" integrity="sha512-7x3zila4t2qNycrtZ31HO0NnJr8kg2VI67YLoRSyi9hGhRN66FHYWr7Axa9Y1J9tGYHVBPqIjSE1ogHrJTz51g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script><!--  圖片預覽  -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="font-body">

    <!-- home section -->
    <section class="bg-white py-10 md:mb-10">

        <div class="container max-w-screen-xl mx-auto px-4">

            <nav class="flex-wrap lg:flex items-center" x-data="{navbarOpen:false}">
                <div class="flex items-center mb-10 lg:mb-0">
                    <img src="{{asset('images/book-4-fix.png')}}" alt="Logo">

                    <button class="lg:hidden w-10 h-10 ml-auto flex items-center justify-center border border-blue-500 text-blue-500 rounded-md" @click="navbarOpen = !navbarOpen">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                </div>

                <ul class="lg:flex flex-col lg:flex-row lg:items-center lg:mx-auto lg:space-x-8 xl:space-x-14" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
                    <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                        <a href="/">首頁</a>
                    </li>
                    <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                        <a href="{{route('products.index')}}">商品</a>
                    </li>
                </ul>

                <div class="lg:flex flex-col md:flex-row md:items-center text-center md:space-x-6" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
                    @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-3xl leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <img width="65" height="65" src="{{asset('images/account.png')}}" alt="">
                                    <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 111.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('products.create')">
                                {{ __('使用者後台') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('admin.message')">
                            {{ __('管理者後台') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>          
                    </x-dropdown>
                    @else
                    <a href="/register" class="px-6 py-4 bg-blue-500 text-white font-semibold text-lg rounded-xl hover:bg-blue-700 transition ease-in-out duration-500 mb-5 md:mb-0">註冊</a>
                    <a href="/login" class="px-6 py-4 border-2 border-blue-500 text-blue-500 font-semibold text-lg rounded-xl hover:bg-blue-700 hover:text-white transition ease-linear duration-500">登入</a>
                    @endauth
                </div>
            </nav>

<style>:root{--background:0 0% 100%;--foreground:240 10% 3.9%;--card:0 0% 100%;--card-foreground:240 10% 3.9%;--popover:0 0% 100%;--popover-foreground:240 10% 3.9%;--primary:240 5.9% 10%;--primary-foreground:0 0% 98%;--secondary:240 4.8% 95.9%;--secondary-foreground:240 5.9% 10%;--muted:240 4.8% 95.9%;--muted-foreground:240 3.8% 45%;--accent:240 4.8% 95.9%;--accent-foreground:240 5.9% 10%;--destructive:0 72% 51%;--destructive-foreground:0 0% 98%;--border:240 5.9% 90%;--input:240 5.9% 90%;--ring:240 5.9% 10%;--chart-1:173 58% 39%;--chart-2:12 76% 61%;--chart-3:197 37% 24%;--chart-4:43 74% 66%;--chart-5:27 87% 67%;--radius:0.5rem;}img[src="/placeholder.svg"],img[src="/placeholder-user.jpg"]{filter:sepia(.3) hue-rotate(-60deg) saturate(.5) opacity(0.8) }</style>
<style>h1, h2, h3, h4, h5, h6 { font-family: 'Inter', sans-serif; --font-sans-serif: 'Inter'; }
</style>
<style>body { font-family: 'Inter', sans-serif; --font-sans-serif: 'Inter'; }
</style>
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
                    <button id="leftArrow" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-r">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button id="rightArrow" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-l">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            @else
                <div>沒有圖片</div>
            @endif

            {{-- 縮略圖區域 --}}
            <div class="flex justify-start mt-4 space-x-4">
                @foreach($product->getMedia('images') as $index => $media)
                    <div class="w-24 h-24 border-2 border-gray-300 flex items-center justify-center cursor-pointer thumbnail" data-index="{{ $index }}">
                        <img 
                            src="{{ $media->getUrl() }}" 
                            alt="Thumbnail {{ $index + 1 }}" 
                            class="max-w-full max-h-full object-cover"
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
                    <h1 class="text-3xl font-bold break-words">商品名稱:{{ $product->name }}</h1>
                    <div class="mt-2">
                        <h2 class="font-semibold text-xl">用戶名稱:{{ $product->user->name }}</h2>
                    </div>
                </div>
                <button id="reportButton" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded flex-shrink-0">
                    檢舉
                </button>
            </div>
            <!-- 其他商品信息 -->
            <p class="text-2xl font-bold">${{ $product->price }}</p>
            <p>上架時間: {{ $product->created_at->format('Y-m-d H:i:s') }}</p>
            <p class="text-muted-foreground text-2xl">商品介紹: {{ $product->description }}</p>
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
        <form method="POST" action="{{ route('products.chirps.store', ['product' => $product->id]) }}">
            @csrf
            <textarea
                name="message"
                placeholder="{{ __('今天想留點什麼足跡?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->store->get('message')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('留言') }}</x-primary-button>
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
