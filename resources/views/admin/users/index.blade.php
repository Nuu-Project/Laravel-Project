<x-template-admin-layout>
    <script src="{{ asset('js/admin/user.js') }}"></script>

    <!-- 主要內容 -->
    <x-main.flex-container>
        <x-div.container>
            <x-h.h3>用戶管理</x-h.h3>

            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <x-h.h2 id="users-title">用戶</x-h.h2>
                    <form action="{{ route('admin.users.index') }}" method="GET">
                        <div>
                            <input type="text" name="filter[name]" id="filter[name]"
                                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="請輸入用戶名稱..." value="{{ request('filter.name') ?? '' }}">
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </div>
                    </form>
                </div>

                <div id="search-results" class="bg-white shadow overflow-hidden sm:rounded-lg" style="display: none;">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <x-thead.user />
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Search results will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- All users 部分 -->
            <div class="bg-white rounded-lg shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <x-thead.user />
                        <tbody class="bg-white divide-y divide-gray-200">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <span>{{ $user->reports_count ?? 0 }}次</span>
                                            <x-button.red-short>
                                                檢舉詳情
                                            </x-button.red-short>
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </x-div.container>
    </x-main.flex-container>
</x-template-admin-layout>
