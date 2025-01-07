<x-template-admin-layout>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h3 class="text-gray-700 text-3xl font-medium mb-6">新增管理員</h3>

            <!-- 用戶搜索部分 -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 id="users-title" class="text-xl font-semibold text-gray-900">一般用戶</h2>
                    <form action="{{ route('admin.roles.create') }}" method="GET">
                        <div>
                            <input type="text" name="filter[name]" id="filter[name]"
                                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Search..." value="{{ request('filter.name') ?? '' }}">
                            <x-button.search>
                                搜索
                            </x-button.search>
                        </div>
                    </form>
                </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- 表單開始 -->
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf <!-- CSRF 保護 -->
                    <table class="min-w-full divide-y divide-gray-200">
                        <x-thead.roles />
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                @if (!$user->hasRole('admin') && !$user->hasRole('user'))
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <!-- 勾選框，傳遞每個用戶的ID -->
                                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                                class="form-checkbox h-4 w-4 text-blue-600">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->email }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <!-- 分頁 -->
                    <div>
                        {{ $users->links() }}
                    </div>

                    <!-- 提交按鈕 -->
                    <div class="mt-4 flex justify-end space-x-4">
                        <button type="button" id="cancelBtn"
                            class="bg-blue-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            取消
                        </button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            確認
                        </button>
                    </div>
                </form>
            </div>

            <script>
                // 取消按鈕
                document.getElementById('cancelBtn').addEventListener('click', function() {
                    window.location.href = '{{ route('admin.roles.index') }}';
                });
            </script>
        </div>
    </main>
</x-template-admin-layout>
