<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <x-h.h3>標籤管理</x-h.h3>

            <!-- 搜尋區塊 -->
            <div class="mb-8 px-4 sm:px-0">
                <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
                    <div class="w-full lg:w-auto">
                        <a href="{{ route('admin.tags.create') }}" class="block">
                            <x-button.search class="w-full lg:w-auto">新增標籤</x-button.search>
                        </a>
                    </div>
                    <div class="w-full lg:w-auto">
                        <form action="{{ route('admin.tags.index') }}" method="GET"
                            class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4">
                            <x-input.search type="text" name="filter[name]" placeholder="搜尋標籤..."
                                value="{{ request('filter.name') }}" class="w-full lg:w-auto">
                            </x-input.search>
                            <x-button.search class="w-full lg:w-auto">
                                搜尋
                            </x-button.search>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 表格區塊 -->
            <div class="bg-white rounded-lg shadow">
                <div class="overflow-x-auto">
                    <div class="min-w-full">
                        <x-table.tags :tags="$tags">
                        </x-table.tags>
                    </div>
                </div>
                <div class="bg-white px-4 py-3 border-t border-gray-200">
                    {{ $tags->links() }}
                </div>
            </div>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
