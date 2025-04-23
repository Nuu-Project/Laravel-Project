<x-template-admin-layout>
    <script src="{{ asset('js/admin/roles/create.js') }}"></script>

    <x-flex-container>
        <x-div.container>
            <x-h.h3>新增管理員</x-h.h3>

            <div>
                <x-div.flex-container>
                    <x-h.h2 id="users-title">一般用戶</x-h.h2>
                    <div>
                        <form action="{{ route('admin.roles.create') }}" method="GET">
                            <x-form.search-layout>
                                <x-input.search type="text" name="filter[name]" placeholder="搜尋用戶名稱或email..."
                                    value="{{ request('filter.name') }}">
                                </x-input.search>
                                <x-button.search>
                                    搜尋
                                </x-button.search>
                            </x-form.search-layout>
                        </form>
                    </div>
                </x-div.flex-container>

                <!-- 表單開始 -->
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf <!-- CSRF 保護 -->
                    <x-div.bg-white>
                        <x-table.overflow-container class="w-full">
                            <x-table.gray-200>
                                <x-thead.roles />
                                <x-tbody>
                                    @foreach ($users as $user)
                                        @if (!$user->hasRole('admin') && !$user->hasRole('user'))
                                            <x-tr.hover>
                                                <x-td.center-left>
                                                    <x-input.checkbox name="user_ids[]" value="{{ $user->id }}" />
                                                </x-td.center-left>
                                                <x-td>
                                                    {{ $user->name }}
                                                </x-td>
                                                <x-td>
                                                    {{ $user->email }}
                                                </x-td>
                                            </x-tr.hover>
                                        @endif
                                    @endforeach
                                </x-tbody>
                            </x-table.gray-200>
                        </x-table.overflow-container>

                        <x-div.gray-200>
                            {{ $users->links() }}
                        </x-div.gray-200>
                    </x-div.bg-white>

                    <!-- 提交按鈕 -->
                    <x-form.button-group>
                        <x-button.roles type="button" id="cancelBtn" data-url="{{ route('admin.roles.index') }}">
                            取消
                        </x-button.roles>
                        <x-button.roles type="submit">
                            確認
                        </x-button.roles>
                    </x-form.button-group>
                </form>
            </div>
        </x-div.container>
    </x-flex-container>

</x-template-admin-layout>
