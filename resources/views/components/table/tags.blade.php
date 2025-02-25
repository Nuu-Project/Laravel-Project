@props(['tags'])

<x-table.gray-200>
    <x-thead.gray-50>
        <tr>
            <x-th>標籤名稱</x-th>
            <x-th>上傳時間</x-th>
            <x-th>最後修改時間</x-th>
            <x-th>操作</x-th>
            <x-th>狀態</x-th>
        </tr>
    </x-thead.gray-50>
    <x-gray-200>
        @foreach ($tags as $tag)
            <tr>
                <x-gray-900>
                    <x-div.gray-900>{{ $tag->name }}</x-div.gray-900>
                </x-gray-900>
                <x-gray-900>
                    <x-div.gray-900>{{ $tag->created_at }}</x-div.gray-900>
                </x-gray-900>
                <x-gray-900>
                    <x-div.gray-900>{{ $tag->updated_at }}</x-div.gray-900>
                </x-gray-900>
                <x-gray-900>
                    <a href="{{ route('admin.tags.edit', $tag->id) }}">
                        <x-button.blue-short>
                            編輯
                        </x-button.blue-short>
                    </a>
                    @if (!is_null($tag->deleted_at))
                        <form action="{{ route('admin.tags.restore', $tag->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <x-button.blue-short>
                                啟用
                            </x-button.blue-short>
                        </form>
                    @else
                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <x-button.red-short>
                                取消
                            </x-button.red-short>
                        </form>
                    @endif
                </x-gray-900>
                <x-gray-900>
                    {{ is_null($tag->deleted_at) ? '啟用中' : '已停用' }}
                </x-gray-900>
            </tr>
        @endforeach
    </x-gray-200>
</x-table.gray-200>
