<x-template-admin-layout>

    <x-main.flex-container>
        <x-div.container>
            <x-h.h3>新增管理員</x-h.h3>


            <div class="mb-8">
                <x-div.flex-container>
                    <x-h.h2 id="users-title">一般用戶</x-h.h2>
                    <form action="{{ route('admin.roles.create') }}" method="GET">
                        <div>
                            <x-input.search type="text" name="filter[name]" placeholder="搜尋用戶名稱或email..."
                                value="{{ request('filter.name') }}">
                            </x-input.search>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </div>
                    </form>
                </x-div.flex-container>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <!-- 表單開始 -->
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf <!-- CSRF 保護 -->
                        <x-table.gray-200>
                            <x-thead.roles />
                            <x-tbody.gray-200>
                                @foreach ($users as $user)
                                    @if (!$user->hasRole('admin') && !$user->hasRole('user'))
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <!-- 勾選框，傳遞每個用戶的ID -->
                                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                                    class="form-checkbox h-4 w-4 text-blue-600">
                                            </td>
                                            <x-td.gray-900>
                                                {{ $user->name }}
                                            </x-td.gray-900>
                                            <x-td.gray-500>
                                                {{ $user->email }}
                                            </x-td.gray-500>
                                        </tr>
                                    @endif
                                @endforeach
                            </x-tbody.gray-200>
                        </x-table.gray-200>

                        <!-- 分頁 -->
                        <div>
                            {{ $users->links() }}
                        </div>

                        <!-- 提交按鈕 -->
                        <div class="mt-4 flex justify-end space-x-4">
                            <x-button.roles type="button" id="cancelBtn">
                                取消
                            </x-button.roles>
                            <x-button.roles type="submit">
                                確認
                            </x-button.roles>
                        </div>
                    </form>
                </div>

                <script>
                    // 取消按鈕
                    document.getElementById('cancelBtn').addEventListener('click', function() {
                        window.location.href = '{{ route('admin.roles.index') }}';
                    });
                </script>
        </x-div.container>
    </x-main.flex-container>
</x-template-admin-layout>
