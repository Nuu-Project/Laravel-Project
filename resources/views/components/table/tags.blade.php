@props(['tags'])

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <x-th.name>標籤名稱</x-th.name>
            <x-th.name>上傳時間</x-th.name>
            <x-th.name>最後修改時間</x-th.name>
            <x-th.name>操作</x-th.name>
            <x-th.name>狀態</x-th.name>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($tags as $tag)
            <tr>
                <x-td.gray-900>
                    <x-div.gray-900>{{ $tag->name }}</x-div.gray-900>
                </x-td.gray-900>
                <x-td.gray-900>
                    <x-div.gray-900>{{ $tag->created_at }}</x-div.gray-900>
                </x-td.gray-900>
                <x-td.gray-900>
                    <x-div.gray-900>{{ $tag->updated_at }}</x-div.gray-900>
                </x-td.gray-900>
                <x-td.gray-900>
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
                </x-td.gray-900>
                <x-td.gray-500>
                    {{ is_null($tag->deleted_at) ? '啟用中' : '已停用' }}
                </x-td.gray-500>
            </tr>
        @endforeach
    </tbody>
</table>
