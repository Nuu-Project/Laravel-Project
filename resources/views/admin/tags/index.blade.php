<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-main.flex-container>
        <div class="max-w-7xl mx-auto">
            <x-h.h3>標籤管理</x-h.h3>
            <div class="p-4">
                <a href="{{ route('admin.tags.create') }}"><button
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">新增標籤</button></a>
            </div>
            <div class="bg-white rounded-lg shadow">
                <div class="overflow-x-auto">
                    <x-table.tags :tags="$tags">
                    </x-table.tags>

                    <x-div.gray-200>
                        {{ $tags->links() }}
                    </x-div.gray-200>
                </div>
            </div>
        </div>
    </x-main.flex-container>
</x-template-admin-layout>
