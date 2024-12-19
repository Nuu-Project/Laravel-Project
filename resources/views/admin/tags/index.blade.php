<x-template-admin-layout>

    <!-- 主要內容 -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-4">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-gray-700 text-3xl font-medium mb-6">標籤管理</h3>
            <div class="p-4">
                <a href="{{ route('admin.tags.create') }}"><button
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">新增標籤</button></a>
            </div>
            <div class="overflow-x-auto">
                <x-table.tags :tags="$tags">
                </x-table.tags>
            </div>
        </div>
        </div>
        </div>
        </div>
    </main>
</x-template-admin-layout>
