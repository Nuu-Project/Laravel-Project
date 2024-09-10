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
</head>

<body class="font-body">

    <!-- home section -->
    <section class="bg-white py-10 md:mb-10">

    <div class="container max-w-screen-xl mx-auto px-4">

<nav class="flex-wrap lg:flex items-center" x-data="{navbarOpen:false}">
    <div class="flex items-center mb-10 lg:mb-0">
        <img src="images/book-4-fix.png" alt="Logo">

        <button class="lg:hidden w-10 h-10 ml-auto flex items-center justify-center border border-blue-500 text-blue-500 rounded-md" @click="navbarOpen = !navbarOpen">
            <i data-feather="menu"></i>
        </button>
    </div>

   
    
    
    @auth
    <ul class="lg:flex flex-col lg:flex-row lg:items-center lg:mx-auto lg:space-x-8 xl:space-x-14" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
    <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
            <a href="/">首頁</a>
        </li>
        <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
            <a href="{{route('products.index')}}">商品</a>
        </li>
        <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
            <a href="/user-product-create">刊登</a>
        </li>
        <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
            <a href="/user-product-check">我的商品</a>
        </li>  
    </ul>
    @else
    <ul class="lg:flex flex-col lg:flex-row lg:items-center lg:mx-auto lg:space-x-8 xl:space-x-14" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
        <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
            <a href="/">首頁</a>
        </li>
        <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
            <a href="/product">商品</a>
        </li>
    </ul>
    @endauth

    <div class="lg:flex flex-col md:flex-row md:items-center text-center md:space-x-6" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
        @auth
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-3xl leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <img width="65" height="65" src="images/account.png" alt="">    
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
                    {{ __('個人資料') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('登出') }}
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
            <!-- </div>
            </section> -->

            <style>
                :root {
                    --background: 0 0% 100%;
                    --foreground: 240 10% 3.9%;
                    --card: 0 0% 100%;
                    --card-foreground: 240 10% 3.9%;
                    --popover: 0 0% 100%;
                    --popover-foreground: 240 10% 3.9%;
                    --primary: 240 5.9% 10%;
                    --primary-foreground: 0 0% 98%;
                    --secondary: 240 4.8% 95.9%;
                    --secondary-foreground: 240 5.9% 10%;
                    --muted: 240 4.8% 95.9%;
                    --muted-foreground: 240 3.8% 45%;
                    --accent: 240 4.8% 95.9%;
                    --accent-foreground: 240 5.9% 10%;
                    --destructive: 0 72% 51%;
                    --destructive-foreground: 0 0% 98%;
                    --border: 240 5.9% 90%;
                    --input: 240 5.9% 90%;
                    --ring: 240 5.9% 10%;
                    --chart-1: 173 58% 39%;
                    --chart-2: 12 76% 61%;
                    --chart-3: 197 37% 24%;
                    --chart-4: 43 74% 66%;
                    --chart-5: 27 87% 67%;
                    --radius: 0.5rem;
                }

                img[src="/placeholder.svg"],
                img[src="/placeholder-user.jpg"] {
                    filter: sepia(.3) hue-rotate(-60deg) saturate(.5) opacity(0.8)
                }
            </style>
            <style>
                h1,
                h2,
                h3,
                h4,
                h5,
                h6 {
                    font-family: 'Inter', sans-serif;
                    --font-sans-serif: 'Inter';
                }
            </style>
            <style>
                body {
                    font-family: 'Inter', sans-serif;
                    --font-sans-serif: 'Inter';
                }
            </style>
            
            <div class="flex flex-wrap gap-2 justify-center">
  <a
    class="bg-gray text-primary-foreground px-4 py-2 rounded-md hover:text-blue-400 transition ease-in-out duration-300"
    href="#"
  >
    #Clothing
  </a>
  <a
    class="bg-gray text-primary-foreground px-4 py-2 rounded-md hover:text-blue-400 transition ease-in-out duration-300"
    href="#"
  >
    #Clothing
  </a>
  <a
    class="bg-gray text-primary-foreground px-4 py-2 rounded-md hover:text-blue-400 transition ease-in-out duration-300"
    href="#"
  >
    #Clothing
  </a>
  <a
    class="bg-gray text-primary-foreground px-4 py-2 rounded-md hover:text-blue-400 transition ease-in-out duration-300"
    href="#"
  >
    #Clothing
  </a>
</div>


            <div class="flex flex-col w-full min-h-screen">
                <main class="flex min-h-[calc(100vh_-_theme(spacing.16))] flex-1 flex-col gap-4 p-4 md:gap-8 md:p-10">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($products as $product)
                            <div class="rounded-lg border bg-card text-card-foreground shadow-sm" data-v0-t="card">
                                <div class="space-y-1.5 p-6">
                                    <h4 class="font-semibold text-2xl mb-2">商品名稱:{{$product->name}}</h4>
                                    <div><h1 class="font-semibold">用戶名稱:{{ $product->user->name }}</h1></div>
                                </div>
                                <div class="p-6">
                                    <div class="text-2xl font-bold">${{$product->price}}</div>
                                    <h1 class="font-semibold">上架時間: {{$product->updated_at->format('Y/m/d')}}</h1>
                                    <p class="font-semibold text-sm mt-2">{{$product->description}}</p>
                                    <div class="mt-4">
                                        @if($product->media->isNotEmpty())
                                            @php
                                                $media = $product->getFirstMedia('images');
                                            @endphp
                                            @if($media)
                                                <img src="{{ asset('images/' . $media->file_name) }}" alt="這是圖片" width="1200" height="900" style="aspect-ratio: 900 / 1200; object-fit: cover;" class="w-full rounded-md object-cover" />
                                            @else
                                            <div>沒圖片</div>
                                            @endif
                                        @else
                                            <div>沒有圖片</div>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between mb-8">
                                        <h6 class="font-black text-gray-600 text-sm md:text-lg">年級 : <span class="font-semibold text-gray-900 text-md md:text-lg">大一【上】</span></h6>
                                        <h6 class="font-black text-gray-600 text-sm md:text-lg">課程 : <span class="font-semibold text-gray-900 text-md md:text-lg">必修</span></h6>
                                 </div>
                                </div>
                                <div class="flex items-center p-6">
                                    <a href="{{route ('products.info')}}"><button class="inline-flex items-center justify-center whitespace-nowrap rounded-xl text-lg font-semibold ring-offset-background transition-colors ease-in-out duration-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-blue-500 text-white hover:bg-blue-700 h-10 px-3 py-2 ml-auto">
                                        洽談
                                    </button>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                </main>
            </div>

</body>

</html>