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
                    <div class="grid gap-6 md:gap-8">
                        <div class="grid gap-2">
                            <h1 class="text-2xl sm:text-3xl font-bold">新增刊登商品</h1>
                            <p class="text-sm sm:text-base text-muted-foreground">請依照下順序進行填寫，照片上傳張數最多五張。</p>
                            <p class="text-sm sm:text-base text-muted-foreground">圖片最左邊將會是商品首圖。</p>
                        </div>
                        <form class="grid gap-6" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
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
                                <label class="text-sm font-medium leading-none" for="grade">年級</label>
                                <select id="grade" name="grade" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option selected>選擇適用的年級...</option>
                                    @foreach($tags as $tag)
                                        @if($tag->type === '年級')
                                            <option value="{{ $tag->getTranslation('slug', 'zh') }}">{{ $tag->getTranslation('name', 'zh') }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid gap-2">
                                <label class="text-sm font-medium leading-none" for="semester">學期</label>
                                <select id="semester" name="semester" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option selected>選擇學期...</option>
                                    @foreach($tags as $tag)
                                        @if($tag->type === '學期')
                                            <option value="{{ $tag->getTranslation('slug', 'zh') }}">{{ $tag->getTranslation('name', 'zh') }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid gap-2">
                                <label class="text-sm font-medium leading-none" for="category">課程類別</label>
                                <select id="category" name="category" class="w-full h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                    <option selected>選擇課程類別...</option>
                                    @foreach($tags as $tag)
                                        @if($tag->type === '課程')
                                            <option value="{{ $tag->getTranslation('slug', 'zh') }}">{{ $tag->getTranslation('name', 'zh') }}</option>
                                        @endif
                                    @endforeach
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
                            
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="relative w-full aspect-square">
                                            <input type="file" name="images[]" id="image{{ $i }}" class="hidden" accept="image/*" onchange="previewImage(this, {{ $i }})">
                                            <label for="image{{ $i }}" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                    </svg>
                                                    <p class="text-sm text-gray-500">新增圖片 {{ $i }}</p>
                                                </div>
                                                <img id="preview{{ $i }}" src="#" alt="預覽圖片" class="hidden w-full h-full object-cover absolute inset-0">
                                            </label>
                                            <button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 m-1 hidden" id="deleteButton{{ $i }}" onclick="removeImage({{ $i }})">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <button class="inline-flex items-center justify-center whitespace-nowrap rounded-xl text-base sm:text-lg font-semibold ring-offset-background transition-colors ease-in-out duration-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-blue-500 text-white hover:bg-blue-700 h-10 sm:h-11 px-4 sm:px-8" type="submit" id="submitButton">
                                刊登商品
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    function previewImage(input, number) {
        const preview = document.getElementById('preview' + number);
        const label = preview.parentElement;
        const deleteButton = document.getElementById('deleteButton' + number);
        const file = input.files[0];
        const reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
            preview.classList.remove('hidden');
            label.querySelector('div').classList.add('hidden');
            deleteButton.classList.remove('hidden');
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
            preview.classList.add('hidden');
            label.querySelector('div').classList.remove('hidden');
            deleteButton.classList.add('hidden');
        }
    }

    function removeImage(number) {
        const input = document.getElementById('image' + number);
        const preview = document.getElementById('preview' + number);
        const label = preview.parentElement;
        const deleteButton = document.getElementById('deleteButton' + number);

        input.value = '';
        preview.src = "";
        preview.classList.add('hidden');
        label.querySelector('div').classList.remove('hidden');
        deleteButton.classList.add('hidden');
    }

    document.getElementById('productForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        // 檢查所有必填欄位
        var requiredFields = ['name', 'price', 'grade', 'semester', 'category', 'description'];
        var allFieldsFilled = true;
        
        for (var i = 0; i < requiredFields.length; i++) {
            var field = document.getElementById(requiredFields[i]);
            if (!field.value) {
                allFieldsFilled = false;
                break;
            }
        }
        
        if (!allFieldsFilled) {
            alert('請填寫所有必填欄位！');
        } else {
            // 所有欄位都已填寫，提交表單
            alert('商品已成功刊登！');
            this.submit();
        }
    });
    </script>
</body>
</html>
