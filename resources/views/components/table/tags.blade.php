@props(['tags'])

<x-table.gray-200>
    <x-thead.gray-50>
        <tr>
            <x-td>標籤名稱</x-td>
            <x-td>上傳時間</x-td>
            <x-td>最後修改時間</x-td>
            <x-td>操作</x-td>
            <x-td>狀態</x-td>
        </tr>
    </x-thead.gray-50>
    <x-tbody>
        @foreach ($tags as $tag)
            <tr>
                <x-td>
                    <x-div.gray-900>{{ $tag->name }}</x-div.gray-900>
                </x-td>
                <x-td>
                    <x-div.gray-900>{{ $tag->created_at }}</x-div.gray-900>
                </x-td>
                <x-td>
                    <x-div.gray-900>{{ $tag->updated_at }}</x-div.gray-900>
                </x-td>
                <x-td>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.tags.edit', $tag->id) }}">
                            <x-button.blue-short>
                                編輯
                            </x-button.blue-short>
                        </a>
                        @if (!is_null($tag->deleted_at))
                            <form action="{{ route('admin.tags.restore', $tag->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <x-button.blue-short>
                                    啟用
                                </x-button.blue-short>
                            </form>
                        @else
                            <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" class="inline">
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
                    {{ is_null($tag->deleted_at) ? '啟用中' : '已停用' }}
                </x-td>
            </tr>
        @endforeach
    </x-gray-200>
</x-table.gray-200>
