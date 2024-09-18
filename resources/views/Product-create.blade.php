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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>     <!--  圖片預覽  -->
</head>

<body class="font-body">

    <!-- home section -->
    <section class="bg-white py-10 md:mb-10">

        <div class="container max-w-screen-xl mx-auto px-4">

            <nav class="flex-wrap lg:flex items-center" x-data="{navbarOpen:false}">
                <div class="flex items-center mb-10 lg:mb-0">
                    <img src="images/book-4-fix.png" alt="Logo">

                    <button class="lg:hidden w-10 h-10 ml-auto flex items-center justify-center border border-blue-500 text-blue-500 rounded-md" @click="navbarOpen = !navbarOpen">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                </div>

                <ul class="lg:flex flex-col lg:flex-row lg:items-center lg:mx-auto lg:space-x-8 xl:space-x-14" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
                    <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                        <a href="/">首頁</a>
                    </li>
                    <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                        <a href="/product">商品</a>
                    </li>
                    <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                        <a href="/user-product-create">刊登</a>
                    </li>
                    <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                        <a href="/user-product-check">我的商品</a>
                    </li>
                </ul>

                <div class="lg:flex flex-col md:flex-row md:items-center text-center md:space-x-6" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
                    @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-3xl leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <img width="65" height="65" src="images/account.png" alt="">
                                    <div>{{ Auth::user()->name}}</div>

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
                    <a href="/login" class="px-6 py-4 border-2 border-blue-500 text-blue-500 font-semibold text-lg rounded-xl hover:bg-blue-700 hover:text-white transition ease-linear duration-500">登入</a>
                    @endauth
                </div>
            </nav>
            <!-- </div> -->
            <!-- </section> -->
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
            <!-- 圖片預覽 -->
            <script>
                $(document).ready(function() {
                    $('#image').change(function() {
                        let reader = new FileReader();
                        reader.onload = (e) => {
                            $('#preview').attr('src', e.target.result).show();
                        }
                        reader.readAsDataURL(this.files[0]);
                    });
                });
            </script>
            <!-- 圖片預覽 -->

            <div class="max-w-2xl mx-auto px-4 py-8 md:px-6 md:py-12">
                <div class="grid gap-6 md:gap-8">
                    <div class="grid gap-2">
                        <h1 class="text-3xl font-bold">新增刊登商品</h1>
                        <p class="text-muted-foreground">請依照下順序進行填寫，照片上傳張數最多五張。</p>
                    </div>
                    <form class="grid gap-6" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid gap-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="name">
                                書名
                            </label>
                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="name" name="name" placeholder="請輸入書名" />
                        </div>
                        <div class="grid gap-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="price">
                                價格
                            </label>
                            <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="price" name="price" placeholder="輸入價格" type="number" />
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="userTown">
                                            年級
                            </label>
                        <select id="userTown" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <option selected>選擇適用的年級...</option>
                            <option>一年級</option>
                            <option>二年級</option>
                            <option>三年級</option>
                            <option>四年級</option>
                            <option>其他</option>
                        </select>
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="userTown">
                                            學期
                            </label>
                        <select id="userTown" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <option selected>學期</option>
                            <option>上學期</option>
                            <option>下學期</option>
                        </select>
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="userTown">
                                            類別
                            </label>
                        <select id="userTown" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <option selected>課程類別</option>
                            <option>必修</option>
                            <option>選修</option>
                            <option>其他</option>
                        </select>
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="description">
                                商品介紹
                            </label>
                            <textarea class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="description" name="description" placeholder="請填寫有關該書的書況or使用情況等等~~" rows="4"></textarea>
                        </div>
                        <div class="grid gap-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="image">
                                上傳圖片
                            </label>
                            <div class="flex items-center gap-4">
                                <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="image" name="image" type="file" />
                            </div>
                            <!-- 圖片預覽 -->
                            <br>
                            <img id="preview" src="#" alt="你的圖片預覽" style="display: none; max-width: 300px;">
                            <br>
                            <!-- 圖片預覽 -->
                        </div>
                        <button class="inline-flex items-center justify-center whitespace-nowrap rounded-xl text-lg font-semibold ring-offset-background transition-colors ease-in-out duration-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-blue-500 text-white hover:bg-blue-700 h-11 px-8" type="submit">
                            刊登商品
                        </button>
                    </form>
</body>

</html>