@props(['reportTypes'])

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
                                @method('PATCH')
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
    </x-tbody>
</x-table.gray-200>
