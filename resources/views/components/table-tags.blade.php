@props(['tags'])

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">標籤名稱</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">上傳時間</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">最後修改時間</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">狀態</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($tags as $tag)
            <tr>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    <div class="text-sm leading-5 font-medium text-gray-900">{{ $tag->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    <div class="text-sm leading-5 text-gray-900">{{ $tag->created_at }}</div>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    <div class="text-sm leading-5 text-gray-900">{{ $tag->updated_at }}</div>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                    <a href="{{ route('admin.tags.edit', $tag->id) }}">
                        <button
                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 mr-2">
                            編輯
                        </button>
                    </a>
                    <form action="{{ route('admin.tags.restore', $tag->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button
                            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 mr-2">
                            啟用
                        </button>
                    </form>
                    <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button
                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                            取消
                        </button>
                    </form>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ is_null($tag->deleted_at) ? '啟用中' : '已停用' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
