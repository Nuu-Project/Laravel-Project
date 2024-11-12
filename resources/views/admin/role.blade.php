<!DOCTYPE html>
<html lang="en">

<head>
    <x-head />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-body">
    <div class="flex flex-col md:flex-row h-screen bg-gray-100">
        <x-admin-link />

        <!-- 主要內容區 -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- 頂部導航欄 -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-end">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-2xl leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
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
        <h3 class="text-gray-700 text-3xl font-medium mb-6">權限管理</h3>
        <!-- 角色新增表單 -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    角色名稱
                </label>
                <input type="text" name="name" id="name" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <!-- 權限設定 -->
                                <div class="mb-4">
                                    <h4 class="text-gray-700 text-lg font-medium mb-4">權限設定</h4>
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    權限名稱
                                                </th>
                                                <th
                                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    選擇
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($permissions as $permission)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $permission->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="relative">
                                                            <input type="checkbox" value="{{ $permission->name }}"
                                                                name="permission[]"
                                                                id="permission_{{ $permission->id }}"
                                                                class="form-checkbox h-5 w-5 text-blue-600">
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- 使用者選擇 -->
                                <div class="mb-4">
                                    <h4 class="text-gray-700 text-lg font-medium mb-4">選擇使用者</h4>
                                    <select name="users[]" multiple
                                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        size="10">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="button" onclick="addRole()"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    新增角色
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 角色列表 -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h4 class="text-gray-700 text-xl font-medium mb-4">現有角色</h4>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        角色名稱</th>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        操作</th>
                                </tr>
                            </thead>
                            <tbody id="roleList" class="bg-white divide-y divide-gray-200">
                                <!-- JavaScript 將在這裡添加角色 -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

            <script>
                function addRole() {
                    const roleName = document.getElementById('name').value;
                    if (!roleName) return;

                    const roleList = document.getElementById('roleList');
                    const newRow = document.createElement('tr');

                    newRow.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            ${roleName}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button class="text-blue-600 hover:text-blue-900 mr-3">編輯</button>
            <button class="text-red-600 hover:text-red-900">刪除</button>
        </td>
    `;

                    roleList.appendChild(newRow);
                    document.getElementById('name').value = ''; // 清空輸入框
                }
            </script>
