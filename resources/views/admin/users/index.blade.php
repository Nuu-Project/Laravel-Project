<x-template-admin-layout>
    <script src="{{ asset('js/admin/user.js') }}"></script>

    <!-- 主要內容 -->
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
                                <!-- Search results will be dynamically inserted here -->
                            </x-gray-200>
                        </x-table.gray-200>
                    </div>
                </x-div.bg-white>
            </div>

            <!-- All users 部分 -->
            <x-div.bg-white>
                <div class="overflow-x-auto">
                    <x-table.users :users="$users" />

                    <x-div.gray-200>
                        {{ $users->links() }}
                    </x-div.gray-200>
                </div>
            </x-div.bg-white>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
