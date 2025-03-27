<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <x-h.h3>標籤管理</x-h.h3>

            <!-- 搜尋區塊 -->
            <div class="mb-6">
                <x-div.flex-container>
                    <div class="w-full sm:w-auto">
                        <a href="{{ route('admin.tags.create') }}" class="block">
                            <x-button.search>新增標籤</x-button.search>
                        </a>
                    </div>
                    <div class="w-full sm:w-auto">
                        <form action="{{ route('admin.tags.index') }}" method="GET"
                            class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <x-input.search type="text" name="filter[name]" placeholder="搜尋標籤..."
                                value="{{ request('filter.name') }}">
                            </x-input.search>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </form>
                    </div>
                </x-div.flex-container>
            </div>

            <!-- 表格區塊 -->
            <x-div.bg-white>
                <div class="overflow-x-auto">
                    <div class="min-w-full">
                        <x-table.tags :tags="$tags">
                        </x-table.tags>
                    </div>
                </div>
                <x-div.gray-200>
                    {{ $tags->links() }}
                </x-div.gray-200>
            </x-div.bg-white>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
