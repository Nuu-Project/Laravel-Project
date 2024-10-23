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
                        <span>管理</span>
                        <svg :class="{'transform rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div x-show="open" class="pl-4">
                    <a href="/admin-search" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">管理</a>
                    <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">下架商品</a>
                    <a href="{{route('admin.user.index')}}" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">用戶管理</a>
                    <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">帳號與留言</a>
                    <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">新增標籤與刪除標籤</a>
                </div>
            </nav>
        </div>

        <!-- 主要內容區 -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- 頂部導航欄 -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-end">
                    @auth
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
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">登入</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">註冊</a>
                    @endif
                    @endauth
                </div>
            </header>

            <!-- 主要內容 -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <h3 class="text-gray-700 text-3xl font-medium mb-6">用戶管理</h3>
                    
                    <!-- 用戶搜索部分 -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h2 id="users-title" class="text-xl font-semibold text-gray-900">用戶</h2>
                            <div>
                                <label for="search-users" class="sr-only">搜索用戶</label>
                                <input type="text" id="search-users" class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm" placeholder="Search...">
                            </div>
                        </div>

                        <!-- 搜索結果 -->
                        <div id="search-results" class="bg-white shadow overflow-hidden sm:rounded-lg" style="display: none;">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <!-- Search results will be dynamically inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- All users 部分 -->
                    <div>
    <h2 class="text-xl font-semibold text-gray-900 mb-4">所有用戶</h2>
    <div id="all-users-list" class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">用戶名稱</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">檢舉</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">權限</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">停用</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ asset('images/account.png') }}" alt="{{ $user->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">{{ $user->reports_count ?? 0 }}次</span>
                                <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-blue-700">檢舉</button>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.update', $user->id) }}" method="POST">
                                @csrf
                                <select name="role" class="bg-gray text-primary-foreground px-4 py-2 rounded-md" onchange="this.form.submit()">
                                    <option value="user" {{ $user->hasRole('user') ? 'selected' : '' }}>使用者</option>
                                    <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>管理者</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="showSuspendDialog({{ $user->id }}, {{ json_encode($user->name) }})" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">停用</button>                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
                    
                </div>
            </main>
        </div>
    </div>

    <script>


function showSuspendDialog(userId, userName) {
    Swal.fire({
        html: `
            ${userName}<br><br>
            <strong>停用時間</strong><br>
            <input type="text" id="suspend-reason" class="swal2-input" placeholder="請輸入停用原因">
        `,
        input: 'radio',
        inputOptions: {
            '60': '60秒',
            '300': '5分',
            '600': '10分',
            '3600': '1小時',
            '86400': '1天',
            '604800': '1週'
        },
        inputValidator: (value) => {
            if (!value) {
                return '請選擇一個選項！'
            }
        },
        showCancelButton: true,
        confirmButtonText: '停用',
        cancelButtonText: '取消',
        showCloseButton: true,
        footer: '<a href="#">瞭解更多看要不要用規則之類的</a>'
    }).then((result) => {
        if (result.isConfirmed) {
            var suspendReason = document.getElementById('suspend-reason').value;
            var duration = parseInt(result.value); // 將 duration 轉為整數
            $.ajax({
                url: '{{ route("user.suspend") }}', // 確保這裡的路由正確
                method: 'POST',
                data: {
                    user_id: userId,
                    duration: duration, // 停用的時間（秒）
                    reason: suspendReason, // 停用原因
                    _token: '{{ csrf_token() }}' // 確保 CSRF 保護
                },
                success: function(response) {
                    Swal.fire('用戶已被停用', response.message, 'success');
                },
                error: function(xhr) {
                    Swal.fire('錯誤', '無法暫停用戶', 'error');
                }
            });
        }
    });
}

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-users');
        const searchResults = document.getElementById('search-results');
        const allUsersList = document.getElementById('all-users-list');
        const users = document.querySelectorAll('#all-users-list tbody tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            if (searchTerm.length > 0) {
                searchResults.style.display = 'block';
                allUsersList.style.display = 'none';

                const resultsBody = searchResults.querySelector('tbody');
                resultsBody.innerHTML = '';

                users.forEach(user => {
                    const userName = user.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const userPosition = user.querySelector('td:nth-child(2)').textContent.toLowerCase();

                    if (userName.includes(searchTerm) || userPosition.includes(searchTerm)) {
                        const clonedRow = user.cloneNode(true);
                        resultsBody.appendChild(clonedRow);
                    }
                });
            } else {
                searchResults.style.display = 'none';
                allUsersList.style.display = 'block';
            }
        });
    });
    </script>
</body>
</html>