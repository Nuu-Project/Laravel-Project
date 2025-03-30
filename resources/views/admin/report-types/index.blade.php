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
                    <x-table.gray-200>
                        <thead class="bg-gray-50">
                            <tr>
                                <x-td>檢舉類型名稱</x-td>
                                <x-td>上傳時間</x-td>
                                <x-td>最後修改時間</x-td>
                                <x-td>操作</x-td>
                                <x-td>狀態</x-td>
                            </tr>
                        </thead>
                        <x-tbody>
                            @foreach ($reportTypes as $reportType)
                                <tr>
                                    <x-td>
                                        <x-div.gray-900>{{ $reportType->name }}</x-div.gray-900>
                                    </x-td>
                                    <x-td>
                                        <x-div.gray-900>{{ $reportType->created_at }}</x-div.gray-900>
                                    </x-td>
                                    <x-td>
                                        <x-div.gray-900>{{ $reportType->updated_at }}</x-div.gray-900>
                                    </x-td>
                                    <x-td>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.report-types.edit', $reportType->id) }}">
                                                <x-button.blue-short>
                                                    編輯
                                                </x-button.blue-short>
                                            </a>
                                            @if (!is_null($reportType->deleted_at))
                                                <form
                                                    action="{{ route('admin.report-types.restore', $reportType->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <x-button.blue-short>
                                                        啟用
                                                    </x-button.blue-short>
                                                </form>
                                            @else
                                                <form
                                                    action="{{ route('admin.report-types.destroy', $reportType->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-button.red-short>
                                                        取消
                                                    </x-button.red-short>
                                                </form>
                                            @endif
                                        </div>
                                    </x-td>
                                    <x-td>
                                        {{ is_null($reportType->deleted_at) ? '啟用中' : '已停用' }}
                                    </x-td>
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
