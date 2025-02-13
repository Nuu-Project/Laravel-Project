<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <x-h.h3>標籤管理</x-h.h3>
            <div class="p-4">
                <a href="{{ route('admin.tags.create') }}"><x-button.search>新增標籤</x-button.search></a>
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
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
