<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icon.png">
    <title>聯大二手書交易平台</title>
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" integrity="sha512-7x3zila4t2qNycrtZ31HO0NnJr8kg2VI67YLoRSyi9hGhRN66FHYWr7Axa9Y1J9tGYHVBPqIjSE1ogHrJTz51g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="font-body bg-white dark:bg-gray-900">
    <div class="flex min-h-screen">
        <!-- 左側邊欄 -->
        <div class="w-64 bg-white shadow-md dark:bg-gray-800">
            <div class="p-4 text-xl font-bold dark:text-white">管理者後台</div>
            <nav class="mt-4">
                <a href="/admin-search" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700">管理</a>
                <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700">下架商品</a>
                <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700">用戶管理</a>
                <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700">帳號與留言</a>
                <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700">新增標籤與刪除標籤</a>
                
            </nav>
        </div>

        <!-- 主要內容區 -->
        <div class="flex-1 flex flex-col">
            <!-- 頂部導航欄 -->
            <header class="bg-white shadow dark:bg-gray-800">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-end">
                    @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 dark:bg-gray-800 dark:text-gray-300 dark:hover:text-white">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                    @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline dark:text-gray-300">登入</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline dark:text-gray-300">註冊</a>
                    @endif
                    @endauth
                </div>
            </header>

            <!-- 主要內容 -->
            <main class="flex-1 overflow-y-auto p-4">
                <div class="mx-auto max-w-screen-xl">
                    <div class="mx-auto max-w-5xl">
                        <!-- Reviews 搜索部分 -->
                        <section class="mb-8">
                            <div class="gap-4 sm:flex sm:items-center sm:justify-between">
                                <h2 id="reviews-title" class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Reviews</h2>
                                <div class="mt-6 sm:mt-0">
                                    <label for="search-reviews" class="sr-only mb-2 block text-sm font-medium text-gray-900 dark:text-white">搜索留言</label>
                                    <input type="text" id="search-reviews" class="block w-full min-w-[8rem] rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Search...">
                                </div>
                            </div>

                            <!-- 搜索結果 -->
                            <div id="search-results" class="mt-6 flow-root sm:mt-8" style="display: none;">
                                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($chirps as $chirp)
                                    <div class="grid md:grid-cols-12 gap-4 md:gap-6 py-4 md:py-6">
                                        <dl class="md:col-span-3 order-3 md:order-1">
                                            <dt class="sr-only">User:</dt>
                                            <dd class="text-base font-semibold text-gray-900 dark:text-white">
                                                {{ $chirp->user->name }}
                                            </dd>
                                            <dt class="sr-only">Product:</dt>
                                            <dd class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $chirp->product->name ?? 'No associated product' }}
                                            </dd>
                                        </dl>

                                        <dl class="md:col-span-6 order-4 md:order-2">
                                            <dt class="sr-only">Message:</dt>
                                            <dd class="text-gray-500 dark:text-gray-400">{{ $chirp->message }}</dd>
                                        </dl>

                                        <div class="md:col-span-3 content-center order-1 md:order-3 flex items-center justify-between">
                                            <dl>
                                                <dt class="sr-only">Date:</dt>
                                                <dd class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $chirp->created_at->format('M d, Y') }}
                                                </dd>
                                            </dl>
                                            <div>
                                                @if($chirp->product)
                                                    <!-- <a href="{{ route('products.chirps.edit', ['product' => $chirp->product->id, 'chirp' => $chirp->id]) }}" class="text-blue-600 hover:underline dark:text-blue-400">Edit</a> -->
                                                    <form action="{{ route('products.chirps.destroy', ['product' => $chirp->product->id, 'chirp' => $chirp->id]) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:underline ml-2 dark:text-red-400">Delete</button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400">No associated product</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>

                        <!-- All reviews 部分 -->
                        <section>
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-4">All reviews</h2>
    <div id="all-reviews-list" class="mt-6 flow-root sm:mt-8">
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @php
            $currentUser = null;
            @endphp

            @foreach($chirps as $chirp)
                @if($chirp->user)
                    @if($currentUser !== $chirp->user->id)
                        @if($currentUser !== null)
                            </div>
                        @endif
                        @php
                            $currentUser = $chirp->user->id;
                        @endphp
                        <div class="mb-4">
                    @endif
                    <div class="p-6 flex space-x-2 bg-white shadow-sm rounded-lg dark:bg-gray-800">
                        <div class="mr-4 flex-shrink-0 relative">
                            <img width="65" height="65" src="{{ asset('images/account.png') }}" alt="{{ $chirp->user->name }}" class="cursor-pointer" onclick="toggleDropdown('dropdown-{{ $chirp->user->id }}')">
                            <div id="dropdown-{{ $chirp->user->id }}" class="absolute hidden right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 dark:bg-gray-700">
                                <form method="POST" action="{{ route('users.destroy', $chirp->user) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600" onsubmit="return confirm('確定要刪除此用戶嗎？這將刪除所有相關的評論。')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left">刪除帳號</button>
                                </form>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-gray-800 dark:text-gray-200">{{ $chirp->user->name }}</span>
                                    <small class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $chirp->created_at->format('Y/m/d , H:i:s') }}</small>
                                    <small class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                        @if($chirp->product)
                                            {{ $chirp->product->name }}
                                        @else
                                            No associated product
                                        @endif
                                    </small>
                                </div>
                                <div>
                                    @if($chirp->product)
                                        <!-- <a href="{{ route('products.chirps.edit', ['product' => $chirp->product->id, 'chirp' => $chirp->id]) }}" class="text-blue-600 hover:underline dark:text-blue-400">Edit</a> -->
                                        <form action="{{ route('products.chirps.destroy', ['product' => $chirp->product->id, 'chirp' => $chirp->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline ml-2 dark:text-red-400" onclick="return confirm('{{ __('確定要刪除這條評論嗎？') }}')">Delete</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">No associated product</span>
                                    @endif
                                </div>
                            </div>
                            <p class="mt-4 text-lg text-gray-900 dark:text-gray-200">{{ $chirp->message }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
            @if($currentUser !== null)
                </div>
            @endif
        </div>
    </div>
    </section>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-reviews');
        const searchResults = document.getElementById('search-results');
        const allReviewsList = document.getElementById('all-reviews-list');
        const reviews = document.querySelectorAll('.p-6.flex.space-x-2');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            if (searchTerm.length > 0) {
                searchResults.style.display = 'block';
                allReviewsList.style.display = 'none';

                reviews.forEach(review => {
                    const userName = review.querySelector('.text-gray-800').textContent.toLowerCase();
                    const productName = review.querySelector('.text-gray-500').textContent.toLowerCase();
                    const reviewText = review.querySelector('.text-lg.text-gray-900').textContent.toLowerCase();

                    if (userName.includes(searchTerm) || productName.includes(searchTerm) || reviewText.includes(searchTerm)) {
                        review.style.display = '';
                    } else {
                        review.style.display = 'none';
                    }
                });
            } else {
                searchResults.style.display = 'none';
                allReviewsList.style.display = 'block';
            }
        });
    });

    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }

    // 點擊頭像以外的地方關閉下拉菜單
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('[id^="dropdown-"]');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(event.target) && !event.target.closest('.cursor-pointer')) {
                dropdown.classList.add('hidden');
            }
        });
    });

    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    primary: {"50":"#eff6ff","100":"#dbeafe","200":"#bfdbfe","300":"#93c5fd","400":"#60a5fa","500":"#3b82f6","600":"#2563eb","700":"#1d4ed8","800":"#1e40af","900":"#1e3a8a","950":"#172554"}
                }
            },
            fontFamily: {
                'body': [
                    'Inter', 
                    'ui-sans-serif', 
                    'system-ui', 
                    '-apple-system', 
                    'system-ui', 
                    'Segoe UI', 
                    'Roboto', 
                    'Helvetica Neue', 
                    'Arial', 
                    'Noto Sans', 
                    'sans-serif', 
                    'Apple Color Emoji', 
                    'Segoe UI Emoji', 
                    'Segoe UI Symbol', 
                    'Noto Color Emoji'
                ],
                'sans': [
                    'Inter', 
                    'ui-sans-serif', 
                    'system-ui', 
                    '-apple-system', 
                    'system-ui', 
                    'Segoe UI', 
                    'Roboto', 
                    'Helvetica Neue', 
                    'Arial', 
                    'Noto Sans', 
                    'sans-serif', 
                    'Apple Color Emoji', 
                    'Segoe UI Emoji', 
                    'Segoe UI Symbol', 
                    'Noto Color Emoji'
                ]
            }
        }
    }
    </script>
</body>
</html>