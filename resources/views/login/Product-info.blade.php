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
                        <a href="{{route('products.index')}}">商品</a>
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
                    <a href="/login" class="px-6 py-4 border-2 border-blue-500 text-blue-500 font-semibold text-lg rounded-xl hover:bg-blue-700 hover:text-white transition ease-linear duration-500">登入</a>
                    @endauth
                </div>
            </nav>
        
        <style>
        /* .testcontainer {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
        } */

        .product-images {
            width: 40%;
            padding-right: 20px;
        }

        .product-images img {
            width: 70%;
            height: 45%;
            object-fit: cover;
        }

        .thumbnail-container {
            display:flex;
            margin-top: 10px;
        }

        .thumbnail {
            width: 60px;
            height: 60px;
            margin-right: 10px;
            object-fit: cover;
            cursor: pointer;
        }

        .product-info {
            width: 60%;
            padding: 20px;
        }

        .product-title {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .price {
            color: red;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .color-options {
            margin-bottom: 15px;
        }

        .btn {
            padding: 12px 24px;
            margin-right: 15px;
            font-size: 16px;
            cursor: pointer;
        }

        .thumbnail.active {
           border: 2px solid #ee5253;
        }

        #main-image {
            cursor: pointer;
        }

    </style>


    <div class="testcontainer">
        <div class="product-images">
            <img id="main-image" src="images/book-5.jpg" alt="產品圖片">
            <div class="thumbnail-container">
                <img src="images/book-1.jpg" alt="縮略圖1" class="thumbnail" data-src="images/book-1.jpg">
                <img src="images/book-2.jpg" alt="縮略圖2" class="thumbnail" data-src="images/book-2.jpg">
                <img src="images/book-3.jpg" alt="縮略圖3" class="thumbnail" data-src="images/book-3.jpg">
            </div>
        </div>
        <div class="product-info">
            <h1 class="product-title">我是商品名</h1>
            <h1 class="product-title">商品描述</h1>
            <h1 class="product-title">創建日期</h1>
            <h1 class="product-title">最後更新時間</h1>

            <p class="price">
                我可能是底價嗎
            </p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('main-image');
            const thumbnails = document.querySelectorAll('.thumbnail');

            function updateImage(src) {
                mainImage.src = src;
                thumbnails.forEach(t => t.classList.remove('active'));
                Array.from(thumbnails).find(t => t.getAttribute('data-src') === src).classList.add('active');
            }

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    updateImage(this.getAttribute('data-src'));
                });
            });
        });
    </script>
</body>
</html>
