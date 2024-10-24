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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>

    <body class="font-body">
        <div class="flex flex-col md:flex-row h-screen bg-gray-100">
            <!-- 左側邊欄 -->
            <div class="w-full md:w-64 bg-white shadow-md">
            <div class="p-4 text-2xl font-bold">管理者後台</div>
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
                    <a href="{{route('ManageProducts.index')}}" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">商品管理</a>
                    <a href="{{route('admin.user.index')}}" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">用戶管理</a>
                    <a href="{{route('admin.message')}}" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">留言管理</a>
                    <a href="{{route('tags.index')}}" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">新增標籤與刪除標籤</a>
                    <a href="{{route('report.index')}}" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">檢舉詳情</a>
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

                                <!-- <x-dropdown-link :href="route('products.create')">
                                    {{ __('使用者後台') }}
                                </x-dropdown-link> -->

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
                        <h3 class="text-gray-700 text-3xl font-medium mb-6">商品管理</h3>
                        
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">商品名稱</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">刊登者</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">刊登時間</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">最後修改時間</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">檢舉次數</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">狀態</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($products as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->created_at->format('Y/m/d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->updated_at->format('Y/m/d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex flex-row items-center space-x-2">
                                                <a href="{{ route('products.chirps.index', ['product' => $product->id]) }}">
                                                    <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">查看</button>
                                                </a>
                                                <form action="{{ route('DownShelvesController.demote', ['product' => $product->id])  }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="status" value="200"> 
                                                    <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                        下架
                                                    </button>
                                                </form>
                                                <a href="/report"><button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 report-button">檢舉詳情</button></a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($product->status == 100)
                                                    上架中
                                                @elseif($product->status == 200)
                                                    已下架
                                                @else
                                                    未知
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <script>
    window.addEventListener('load', function() {
        var reportButton = document.getElementById('reportbutton');
        if (reportButton) {
            reportButton.addEventListener('click', function() {
                // 假設這是從資料庫獲取的檢舉資料
                var reportData = [
                    { reason: '不當內容', customreason: '名稱極度不雅'       ,date: '2023-05-20' },
                    { reason: '侵犯版權', customreason: '名稱觸犯到版權了'   ,date: '2023-05-21' },
                    { reason: '虛假資訊', customreason: '商品描述與實體不符' ,date: '2023-05-22' }
                ];

                var reportContent = reportData.map(function(report) {
                    return `<div class="mb-2 p-2 bg-gray-100 rounded">
                                <p class="font-bold">${report.reason}</p>
                                <p class="font-bold">${report.customreason}</p>
                                <p class="text-sm text-gray-600">檢舉日期：${report.date}</p>
                            </div>`;
                }).join('');

                Swal.fire({
                    title: '檢舉詳情',
                    html: `<div class="max-h-60 overflow-y-auto">
                              ${reportContent}
                           </div>`,
                    showCloseButton: true,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonText: '確定',
                    customClass: {
                        container: 'swal-wide',
                        popup: 'swal-tall'
                    }
                });
            });
        }
    });
    </script>

    </body>

    </html>
