<x-template-admin-layout>
    <script src="{{ asset('js/roles/index.js') }}"></script>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h3 class="text-gray-700 text-3xl font-medium mb-6">角色管理</h3>

            <!-- Admin 表格 -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <!-- Admin 表格 -->
                <form id="adminForm" action="{{ route('admin.roles.update', ['role' => 'admin']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="role_type" value="admin">

                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-gray-700 text-xl font-medium">管理員列表</h4>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.roles.create', ['type' => 'admin']) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                新增
                            </a>
                            <button type="submit" id="modifyAdminBtn"
                                class="modify-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hidden">
                                修改
                            </button>
                        </div>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    選擇</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    名稱</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                @if ($user->hasRole('admin'))
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $user->id }}"
                                                class="role-checkbox form-checkbox h-4 w-4 text-blue-600"
                                                data-role="admin">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->email }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const checkboxes = document.querySelectorAll('.role-checkbox');

                                checkboxes.forEach(checkbox => {
                                    checkbox.addEventListener('change', function() {
                                        const role = this.dataset.role;
                                        const checkedBoxes = document.querySelectorAll(
                                            `.role-checkbox[data-role="${role}"]:checked`);

                                        const modifyBtn = document.querySelector(
                                            `#modify${role.charAt(0).toUpperCase() + role.slice(1)}Btn`);

                                        // 顯示或隱藏按鈕
                                        modifyBtn.classList.toggle('hidden', checkedBoxes.length === 0);
                                    });
                                });

                                // 當修改按鈕被點擊時提交表單
                                const modifyAdminBtn = document.getElementById('modifyAdminBtn');
                                const modifyUserBtn = document.getElementById('modifyUserBtn');

                                modifyAdminBtn?.addEventListener('click', function() {
                                    document.getElementById('adminForm').submit();
                                });

                                modifyUserBtn?.addEventListener('click', function() {
                                    document.getElementById('userForm').submit();
                                });
                            });
                        </script>
</x-template-admin-layout>
