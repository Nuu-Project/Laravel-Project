<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/icon.png">
    <title>聯大二手書交易平台</title>
    <link rel="stylesheet" href="css/tailwind.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" integrity="sha512-7x3zila4t2qNycrtZ31HO0NnJr8kg2VI67YLoRSyi9hGhRN66FHYWr7Axa9Y1J9tGYHVBPqIjSE1ogHrJTz51g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="font-body">
    <div class="flex flex-col md:flex-row h-screen bg-gray-100">
        <!-- 左側邊欄 -->
        <div class="w-full md:w-64 bg-white shadow-md">
            <div class="p-4 text-2xl font-bold">使用者後台</div>
            <nav class="mt-4" x-data="{ open: false }">
                <div @click="open = !open" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 cursor-pointer">
                    <div class="flex justify-between items-center">
                        <span>商品管理</span>
                        <svg :class="{'transform rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div x-show="open" class="pl-4">
                    <a href="{{route('products.create')}}" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">刊登商品</a>
                    <a href="{{route('products.check')}}" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">我的商品</a>
                </div>
            </nav>
        </div>

        <!-- 主要內容區 -->
        <div class="flex-1 flex flex-col overflow-hidden">
                <!-- 頂部導航欄 -->
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-end">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-2xl leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
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

                                <x-dropdown-link :href="route('products.create')">
                                    {{ __('使用者後台') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('admin.message')">
                                    {{ __('管理者後台') }}
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
                    </div>
                </header>

            <!-- 主要內容 -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <h3 class="text-gray-700 text-3xl font-medium mb-6">我的商品</h3>
                    
                    <div class="flex flex-col w-full min-h-screen">
                        <main class="flex min-h-[calc(100vh_-_theme(spacing.16))] flex-1 flex-col gap-4 p-4 md:gap-8 md:p-10">
                            @if($message)
                                <div class="alert alert-info text-lg font-semibold text-center text-blue-500 p-4">
                                    {{ $message }}
                                </div>
                            @endif    
                            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                                @foreach($userProducts as $product) 
                                    <div class="rounded-lg border bg-white text-card-foreground shadow-sm p-6" data-v0-t="card">
                                        <div class="space-y-2">
                                            <h4 class="font-semibold text-xl">商品名稱:{{$product->name}}</h4>
                                            <div><h1 class="font-semibold text-sm">用戶名稱:{{ $product->user->name }}</h1></div>
                                            @if($product->status == 100)
                                                <div><h1 class="font-semibold text-sm">目前狀態:上架中</h1></div>
                                            @elseif($product->status == 200)   
                                                <div><h1 class="font-semibold text-sm">目前狀態:已下架</h1></div>
                                            @else
                                                <div><h1 class="font-semibold text-sm">目前狀態:未知</h1></div>
                                            @endif
                                        </div>
                                        <div class="mt-4">
                                            <div class="text-2xl font-bold">${{$product->price}}</div>
                                            <h1 class="font-semibold text-sm mt-2">上架時間: {{$product->updated_at->format('Y/m/d')}}</h1>
                                            <p class="font-semibold text-sm mt-2">{{$product->description}}</p>
                                            <div class="mt-4">
                                                @if($product->media->isNotEmpty())
                                                    @php
                                                        $media = $product->getFirstMedia('images');
                                                    @endphp
                                                    @if($media)
                                                    <img src="{{ $media->getUrl() }}" alt="這是圖片" width="1200" height="900" style="aspect-ratio: 900 / 1200; object-fit: cover;" class="w-full rounded-md object-cover" />
                                                    @else
                                                        <div>沒圖片</div>
                                                    @endif
                                                @else
                                                    <div>沒有圖片</div>
                                                @endif
                                            </div>
                                        <div class="flex items-center justify-between mb-8">
                                        <h6 class="font-black text-gray-600 text-sm md:text-lg">年級 : 
                                            <span class="font-semibold text-gray-900 text-md md:text-lg">
                                            @php
                                                $gradeTag = $product->tags->firstWhere('type', '年級');
                                                $semesterTag = $product->tags->firstWhere('type', '學期');
                                            @endphp
                                            {{ $gradeTag ? $gradeTag->getTranslation('name', 'zh') : '無' }}
                                            {{ $semesterTag ? $semesterTag->getTranslation('name', 'zh') : '學期:無' }}
                                            </span>
                                        </h6>
                                        <h6 class="font-black text-gray-600 text-sm md:text-lg">課程 : 
                                            <span class="font-semibold text-gray-900 text-md md:text-lg">
                                                @php
                                                    $categoryTag = $product->tags->firstWhere('type', '課程');
                                                @endphp
                                                {{ $categoryTag ? $categoryTag->getTranslation('name', 'zh') : '無' }}
                                            </span>
                                        </h6>
                             </div>
                                        </div>
                                        <div class="flex justify-center space-x-4 mt-6">
                                            <form action="{{ route('products.demoteData', ['product' => $product->id])  }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="status" value="200"> 
                                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition ease-in-out duration-500">
                                                    下架
                                                </button>
                                            </form>
                                            <a href="{{ route('products.edit', ['product' => $product->id])  }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition ease-in-out duration-500">
                                                編輯
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $userProducts->links() }}
                            </div>
                        </main>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
