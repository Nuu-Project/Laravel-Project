<x-template-admin-layout>
    <x-flex-container>
        <x-div.container>
            <x-h.h3>新增管理員</x-h.h3>

            <div class="mb-8">
                <x-div.flex-container>
                    <x-h.h2 id="users-title">一般用戶</x-h.h2>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                        <form action="{{ route('admin.roles.create') }}" method="GET"
                            class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <x-input.search type="text" name="filter[name]" placeholder="搜尋用戶名稱或email..."
                                value="{{ request('filter.name') }}">
                            </x-input.search>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </form>
                    </div>
                </x-div.flex-container>

                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mt-4">
                    <!-- 表單開始 -->
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf <!-- CSRF 保護 -->
                        <div class="overflow-x-auto -mx-4 sm:mx-0">
                            <x-table.gray-200>
                                <x-thead.roles />
                                <x-tbody>
                                    @foreach ($users as $user)
                                        @if (!$user->hasRole('admin') && !$user->hasRole('user'))
                                            <tr class="hover:bg-gray-50">
                                                <td
                                                    class="px-6 py-4 whitespace-normal sm:whitespace-nowrap text-center sm:text-left">
                                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                                        class="form-checkbox h-4 w-4 text-blue-600">
                                                </td>
                                                <x-td>
                                                    {{ $user->name }}
                                                </x-td>
                                                <x-td>
                                                    {{ $user->email }}
                                                </x-td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </x-tbody>
                            </x-table.gray-200>
                        </div>

                        <!-- 分頁 -->
                        <x-div.gray-200>
                            {{ $users->links() }}
                        </x-div.gray-200>

                        <!-- 提交按鈕 -->
                        <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
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
    </x-flex-container>
</x-template-admin-layout>
