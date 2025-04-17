<x-template-admin-layout>
    <script src="{{ asset('js/admin/user.js') }}"></script>

    <x-flex-container>
        <x-div.container>
            <x-h.h3>用戶管理</x-h.h3>

            <div>
                <x-div.flex-container>
                    <x-h.h2 id="users-title">用戶</x-h.h2>
                    <x-responsive.container>
                        <form action="{{ route('admin.users.index') }}" method="GET">
                            <x-form.search-layout>
                                <x-input.search type="text" name="filter[name]" placeholder="請輸入用戶名稱..."
                                    value="{{ request('filter.name') }}">
                            </x-input.search>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </x-form.search-layout>
                    </form>
                </x-responsive.container>
            </x-div.flex-container>

            <x-div.bg-white id="search-results" style="display: none;">
                <x-table.overflow-container>
                    <x-table.gray-200>
                        <x-thead.user />
                        <x-tbody>
                        </x-tbody>
                    </x-table.gray-200>
                </x-table.overflow-container>
            </x-div.bg-white>

            {{-- All users 部分 --}}
            <x-div.bg-white>
                <x-table.overflow-container>
                    <x-table.users :users="$users" />
                </x-table.overflow-container>
                <x-div.gray-200>
                    {{ $users->links() }}
                </x-div.gray-200>
            </x-div.bg-white>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
