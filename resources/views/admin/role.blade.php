<x-head-layout />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



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
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                新增
                            </button>
                            <button id="adminModifyBtn"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hidden">
                                修改
                            </button>
                        </div>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    選擇
                                </th>
                                <!-- ... 其他表頭 ... -->
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="admin-checkbox form-checkbox h-4 w-4 text-blue-600">
                                </td>
                                <!-- ... 其他欄位 ... -->
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- User 表格 -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-gray-700 text-xl font-medium">使用者列表</h4>
                        <div class="flex gap-2">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                新增
                            </button>
                            <button id="userModifyBtn"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hidden">
                                修改
                            </button>
                        </div>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    選擇
                                </th>
                                <!-- ... 其他表頭 ... -->
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="user-checkbox form-checkbox h-4 w-4 text-blue-600">
                                </td>
                                <!-- ... 其他欄位 ... -->
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- 添加 JavaScript -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // 管理員表格的checkbox控制
                        const adminCheckboxes = document.querySelectorAll('.admin-checkbox');
                        const adminModifyBtn = document.getElementById('adminModifyBtn');

                        adminCheckboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                const hasChecked = Array.from(adminCheckboxes).some(cb => cb.checked);
                                adminModifyBtn.classList.toggle('hidden', !hasChecked);
                            });
                        });

                        // 使用者表格的checkbox控制
                        const userCheckboxes = document.querySelectorAll('.user-checkbox');
                        const userModifyBtn = document.getElementById('userModifyBtn');

                        userCheckboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                const hasChecked = Array.from(userCheckboxes).some(cb => cb.checked);
                                userModifyBtn.classList.toggle('hidden', !hasChecked);
                            });
                        });
                    });
                </script>
</x-head-layout>
