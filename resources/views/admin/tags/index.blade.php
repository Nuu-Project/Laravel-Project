<x-template-admin-layout>

    <x-flex-container>
        <x-div.container>
            <x-h.h3>標籤管理</x-h.h3>

            <div>
                <x-div.flex-container>
                    <x-responsive.container>
                        <a href="{{ route('admin.tags.create') }}" class="block">
                            <x-button.search>新增標籤</x-button.search>
                        </a>
                    </x-responsive.container>
                    <x-responsive.container>
                        <form action="{{ route('admin.tags.index') }}" method="GET">
                            <x-form.search-layout>
                                <x-input.search type="text" name="filter[name]" placeholder="搜尋標籤..."
                                    value="{{ request('filter.name') }}">
                                </x-input.search>
                                <x-button.search>
                                    搜尋
                                </x-button.search>
                            </x-form.search-layout>
                        </form>
                    </x-responsive.container>
                </x-div.flex-container>
            </div>

            <x-div.bg-white>
                <x-table.overflow-container>
                    <x-table.tags :tags="$tags">
                    </x-table.tags>
                </x-table.overflow-container>
                <x-div.gray-200>
                    {{ $tags->links() }}
                </x-div.gray-200>
            </x-div.bg-white>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
