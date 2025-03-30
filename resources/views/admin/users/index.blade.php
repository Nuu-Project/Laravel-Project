<x-template-admin-layout>
    <script src="{{ asset('js/admin/user.js') }}"></script>

    <x-flex-container>
        <x-div.container>
            <x-h.h3>用戶管理</x-h.h3>

            <div class="mb-6">
                <x-div.flex-container>
                    <x-h.h2 id="users-title" class="mb-3 sm:mb-0">用戶</x-h.h2>
                    <div class="w-full sm:w-auto">
                        <form action="{{ route('admin.users.index') }}" method="GET"
                            class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <x-input.search type="text" name="filter[name]" placeholder="請輸入用戶名稱..."
                                value="{{ request('filter.name') }}">
                            </x-input.search>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </form>
                    </div>
                </x-div.flex-container>

                <x-div.bg-white id="search-results" style="display: none;">
                    <div class="overflow-x-auto">
                        <x-table.gray-200>
                            <x-thead.user />
                            <x-tbody>
                            </x-gray-200>
                        </x-table.gray-200>
                    </div>
                </x-div.bg-white>
            </div>

            <x-div.bg-white>
                <div class="overflow-x-auto">
                    <x-table.gray-200>
                        <x-thead.user />
                        <x-tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full"
                                                    src="{{ asset('images/account.png') }}" alt="{{ $user->name }}">
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if ($user->hasRole('admin'))
                                                管理者
                                            @else
                                                使用者
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                        @if ($user->time_limit && \Carbon\Carbon::parse($user->time_limit)->isFuture())
                                            <div class="flex items-center space-x-2">
                                                <x-button.red-short
                                                    onclick="showSuspendDialog({{ $user->id }}, {{ json_encode($user->name) }})">
                                                    停用
                                                </x-button.red-short>
                                                <form action="{{ route('admin.users.active', ['user' => $user->id]) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <x-button.blue-short title="停用到期時間：{{ $user->time_limit }}">
                                                        ({{ \Carbon\Carbon::parse($user->time_limit)->diffForHumans() }})
                                                        啟用
                                                    </x-button.blue-short>
                                                </form>
                                            </div>
                                        @else
                                            <x-button.red-short
                                                onclick="showSuspendDialog({{ $user->id }}, {{ json_encode($user->name) }})">
                                                停用
                                            </x-button.red-short>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </x-gray-200>
                    </x-table.gray-200>

                    <x-div.gray-200>
                        {{ $users->links() }}
                    </x-div.gray-200>
                </div>
            </x-div.bg-white>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
