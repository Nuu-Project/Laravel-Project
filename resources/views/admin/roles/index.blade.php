<x-template-admin-layout>
    <script src="{{ asset('js/roles/index.js') }}"></script>

    <x-flex-container>
        <x-div.container>
            <x-h.h3>角色管理</x-h.h3>

            <!-- Admin 表格 -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <!-- Admin 表格 -->
                <form id="adminForm" action="{{ route('admin.roles.update', ['role' => 'admin']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-gray-700 text-xl font-medium">管理員列表</h4>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.roles.create') }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                新增
                            </a>
                            <button type="submit" id="modifyAdminBtn"
                                class="modify-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hidden">
                                修改
                            </button>
                        </div>
                    </div>

                    <x-table.gray-200>
                        <x-thead.roles />
                        <x-gray-200>
                            @foreach ($users as $user)
                                @if ($user->hasRole('admin'))
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $user->id }}"
                                                class="role-checkbox form-checkbox h-4 w-4 text-blue-600"
                                                data-role="admin">
                                        </td>
                                        <x-gray-900>
                                            {{ $user->name }}
                                        </x-gray-900>
                                        <x-gray-900>
                                            {{ $user->email }}
                                        </x-gray-900>
                                    </tr>
                                @endif
                            @endforeach
                        </x-gray-200>
                    </x-table.gray-200>
                </form>
            </div>
        </x-div.container>
    </x-flex-container>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.role-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll(
                        '.role-checkbox[data-role="admin"]:checked');
                    const modifyBtn = document.querySelector('#modifyAdminBtn');

                    // 顯示或隱藏按鈕
                    modifyBtn.classList.toggle('hidden', checkedBoxes.length === 0);
                });
            });

            // 當修改按鈕被點擊時提交表單
            const modifyAdminBtn = document.getElementById('modifyAdminBtn');
            modifyAdminBtn?.addEventListener('click', function() {
                document.getElementById('adminForm').submit();
            });
        });
    </script>
</x-template-admin-layout>
