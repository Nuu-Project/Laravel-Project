<x-template-admin-layout>
    <script src="{{ asset('js/admin/user.js') }}"></script>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <x-h.h3>用戶管理</x-h.h3>

            <div class="mb-8">
                <x-div.flex-container class="flex-col sm:flex-row space-y-4 sm:space-y-0">
                    <x-h.h2 id="users-title" class="text-center sm:text-left">用戶</x-h.h2>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                        <form action="{{ route('admin.users.index') }}" method="GET"
                            class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <x-input.search type="text" name="filter[name]" placeholder="請輸入用戶名稱..."
                                value="{{ request('filter.name') }}" class="w-full sm:w-auto">
                            </x-input.search>
                            <x-button.search class="w-full sm:w-auto">
                                搜尋
                            </x-button.search>
                        </form>
                    </div>
                </x-div.flex-container>

                <x-div.bg-white id="search-results" style="display: none;">
                    <div class="overflow-x-auto">
                        <x-table.gray-200>
                            <x-thead.user />
                            <x-gray-200>
                                <!-- Search results will be dynamically inserted here -->
                            </x-gray-200>
                        </x-table.gray-200>
                    </div>
                </x-div.bg-white>
            </div>

            <!-- All users 部分 -->
            <div class="bg-white rounded-lg shadow">
                <div class="overflow-x-auto">
                    <x-table.gray-200>
                        <x-thead.user />
                        <x-gray-200>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full"
                                                    src="{{ asset('images/account.png') }}" alt="{{ $user->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if ($user->hasRole('admin'))
                                                管理者
                                            @else
                                                使用者
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <x-button.red-short
                                            onclick="showSuspendDialog({{ $user->id }}, {{ json_encode($user->name) }})">
                                            停用
                                        </x-button.red-short>
                                        <form action="{{ route('admin.users.active', ['user' => $user->id]) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <x-button.blue-short>
                                                啟用
                                            </x-button.blue-short>
                                        </form>
                                        {{ $user->time_limit }}
                                    </td>
                                </tr>
                            @endforeach
                        </x-gray-200>
                    </x-table.gray-200>

                    <x-div.gray-200>
                        {{ $users->links() }}
                    </x-div.gray-200>
                </div>
            </div>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
