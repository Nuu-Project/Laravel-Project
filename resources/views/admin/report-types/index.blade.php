<x-template-admin-layout>

    <x-flex-container>
        <x-div.container>
            <x-h.h3>檢舉類型管理</x-h.h3>

            <div class="mb-8 px-4 sm:px-0">
                <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
                    <div class="w-full lg:w-auto">
                        <a href="{{ route('admin.report-types.create') }}" class="block">
                            <x-button.search class="w-full lg:w-auto">新增標籤</x-button.search>
                        </a>
                    </div>
                    <div class="w-full lg:w-auto">
                        <form action="{{ route('admin.report-types.index') }}" method="GET"
                            class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4">
                            <x-input.search type="text" name="filter[name]" placeholder="搜尋檢舉類型名稱..."
                                value="{{ request('filter.name') }}">
                            </x-input.search>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </form>
                    </div>
                </div>
            </div>

            <x-div.bg-white>
                <div class="overflow-x-auto">
                    <x-table.report-type :reportTypes="$reportTypes" />
                </div>
                <x-div.gray-200>
                    {{ $reportTypes->links() }}
                </x-div.gray-200>
            </x-div.bg-white>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
