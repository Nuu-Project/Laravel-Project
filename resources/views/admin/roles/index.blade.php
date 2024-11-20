<!DOCTYPE html>
<html lang="en">

<head>
    <x-head-layout />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-body">
    <div class="flex flex-col md:flex-row h-screen bg-gray-100">
        <x-side-bar />

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h3 class="text-gray-700 text-3xl font-medium mb-6">角色管理</h3>

                <!-- Admin 表格 -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-gray-700 text-xl font-medium">管理員列表</h4>
                        <div class="flex gap-2">
                            <button id="showUnassignedAdmin"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                新增
                            </button>
                            <button id="adminModifyBtn"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hidden">
                                修改
                            </button>
                        </div>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 admin-table">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    選擇
                                </th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    名稱
                                </th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                @if ($user->hasRole('admin'))
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox"
                                                class="admin-checkbox form-checkbox h-4 w-4 text-blue-600"
                                                data-user-id="{{ $user->id }}">
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
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>

                <!-- User 表格 -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-gray-700 text-xl font-medium">使用者列表</h4>
                        <div class="flex gap-2">
                            <button id="showUnassignedUser"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                新增
                            </button>
                            <button id="userModifyBtn"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hidden">
                                修改
                            </button>
                        </div>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 user-table">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    選擇
                                </th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    名稱
                                </th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                @if ($user->hasRole('user'))
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox"
                                                class="user-checkbox form-checkbox h-4 w-4 text-blue-600"
                                                data-user-id="{{ $user->id }}">
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
                </div>

                <!-- JavaScript -->
                <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Admin 表格複選框處理
        const adminCheckboxes = document.querySelectorAll('.admin-checkbox');
        const adminModifyBtn = document.getElementById('adminModifyBtn');

        adminCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedCount = document.querySelectorAll('.admin-checkbox:checked').length;
                adminModifyBtn.classList.toggle('hidden', checkedCount === 0);
            });
        });

        // User 表格複選框處理
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const userModifyBtn = document.getElementById('userModifyBtn');

        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
                userModifyBtn.classList.toggle('hidden', checkedCount === 0);
            });
        });

        // 處理新增按鈕點擊事件
        document.getElementById('showUnassignedAdmin').addEventListener('click', function() {
            window.location.href = '{{ route("admin.roles.create") }}?type=admin';
        });

        document.getElementById('showUnassignedUser').addEventListener('click', function() {
            window.location.href = '{{ route("admin.roles.create") }}?type=user';
        });

        // 處理修改按鈕點擊事件
        document.getElementById('adminModifyBtn').addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.admin-checkbox:checked'))
                .map(checkbox => checkbox.dataset.userId);
            // 這裡可以添加修改管理員角色的邏輯
        });

        document.getElementById('userModifyBtn').addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                .map(checkbox => checkbox.dataset.userId);
            // 這裡可以添加修改使用者角色的邏輯
        });
    });
</script>
            </div>
        </main>
    </div>
</body>

</html>
