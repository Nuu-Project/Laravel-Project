<x-template-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <div class="flex flex-col md:flex-row h-screen bg-gray-100">
        <x-side-bar />

        <!-- 主要內容區 -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- 頂部導航欄 -->
            <x-navbar-admin />

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <h3 class="text-gray-700 text-3xl font-medium mb-6">角色管理</h3>

                    <!-- Admin 表格 -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <!-- Admin 表格 -->
                        <form id="adminForm" action="{{ route('admin.roles.update', ['role' => 'admin']) }}"
                            method="POST">
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
                                                    <input type="checkbox" name="selected_ids[]"
                                                        value="{{ $user->id }}"
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
                            </table>
                            <div class="mt-4">{{ $users->links() }}</div>
                        </form>
                    </div>

                    <!-- User 表格 -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <form id="userForm" action="{{ route('admin.roles.update', ['role' => 'user']) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="role_type" value="user">

                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-gray-700 text-xl font-medium">使用者列表</h4>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.roles.create', ['type' => 'user']) }}"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        新增
                                    </a>
                                    <button type="submit" id="modifyUserBtn"
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
                                        @if ($user->hasRole('user'))
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" name="selected_ids[]"
                                                        value="{{ $user->id }}"
                                                        class="role-checkbox form-checkbox h-4 w-4 text-blue-600"
                                                        data-role="user">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $user->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $user->email }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $users->links() }}
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>


</x-template-layout>
