<x-template-admin-layout>
    <script src="{{ asset('js/admin/roles/index.js') }}"></script>

    <x-flex-container>
        <x-div.container>
            <x-h.h3>角色管理</x-h.h3>

            <div>
                <x-div.flex-container>
                    <x-h.h2>管理員列表</x-h.h2>
                    <div>
                        <a href="{{ route('admin.roles.create') }}">
                            <x-button.roles>
                                新增
                            </x-button.roles>
                        </a>
                    </div>
                </x-div.flex-container>

                <form id="adminForm" action="{{ route('admin.roles.update', ['role' => 'admin']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <x-div.bg-white>
                        <x-table.overflow-container class="w-full">
                            <x-table.roles :users="$users" />
                        </x-table.overflow-container>
                        <x-div.gray-200>
                            <div class="flex justify-end">
                                <x-button.submit id="modifyAdminBtn" class="modify-btn hidden">
                                    修改
                                </x-button.submit>
                            </div>
                        </x-div.gray-200>
                    </x-div.bg-white>
                </form>
            </div>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
