<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <x-h.h3>檢舉類型管理</x-h.h3>

            <!-- 搜尋區塊在表格之前 -->
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

            <!-- 表格區塊在搜尋區塊之後 -->
            <x-div.bg-white>
                <div class="overflow-x-auto">
                    <x-table.gray-200>
                        <thead class="bg-gray-50">
                            <tr>
                                <x-gray-900>檢舉類型名稱</x-gray-900>
                                <x-gray-900>上傳時間</x-gray-900>
                                <x-gray-900>最後修改時間</x-gray-900>
                                <x-gray-900>操作</x-gray-900>
                                <x-gray-900>狀態</x-gray-900>
                            </tr>
                        </thead>
                        <x-gray-200>
                            @foreach ($reportTypes as $reportType)
                                <tr>
                                    <x-gray-900>
                                        <x-div.gray-900>{{ $reportType->name }}</x-div.gray-900>
                                    </x-gray-900>
                                    <x-gray-900>
                                        <x-div.gray-900>{{ $reportType->created_at }}</x-div.gray-900>
                                    </x-gray-900>
                                    <x-gray-900>
                                        <x-div.gray-900>{{ $reportType->updated_at }}</x-div.gray-900>
                                    </x-gray-900>
                                    <x-gray-900>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.report-types.edit', $reportType->id) }}">
                                            <x-button.blue-short>
                                                編輯
                                            </x-button.blue-short>
                                        </a>
                                        @if (!is_null($reportType->deleted_at))
                                            <form action="{{ route('admin.report_types.restore', $reportType->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <x-button.blue-short>
                                                    啟用
                                                </x-button.blue-short>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.report-types.destroy', $reportType->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <x-button.red-short>
                                                    取消
                                                </x-button.red-short>
                                            </form>
                                        @endif
                                    </div>
                                    </x-gray-900>
                                    <x-gray-900>
                                        {{ is_null($reportType->deleted_at) ? '啟用中' : '已停用' }}
                                    </x-gray-900>
                                </tr>
                            @endforeach
                        </x-gray-200>
                    </x-table.gray-200>
                </div>
                <x-div.bg-white>
                    <x-div.gray-200>
                        {{ $reportTypes->links() }}
                    </x-div.gray-200>
                </x-div.bg-white>
            </x-div.bg-white>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
