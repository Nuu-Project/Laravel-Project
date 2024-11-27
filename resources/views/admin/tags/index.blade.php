<x-template-layout>

        <div class="flex flex-col md:flex-row h-screen bg-gray-100">
        <x-side-bar />

            <!-- 主要內容區 -->
            <div class="flex-1 flex flex-col overflow-hidden">

                <!-- 頂部導航欄 -->
                <x-navbar-admin />

                    <!-- 主要內容 -->
                    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-4">
                        <div class="max-w-7xl mx-auto">
                        <h3 class="text-gray-700 text-3xl font-medium mb-6">標籤管理</h3>
                        <div class="p-4">
                            <a href="{{route('admin.tags.create')}}"><button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">新增標籤</button></a>
                        </div>
                        <div class="overflow-x-auto">
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
                                    @foreach($tags as $tag)
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
                                                            <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 mr-2">
                                                                編輯
                                                            </button>
                                                        </a>
                                                        <form action="{{ route('admin.tags.restore', $tag->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 mr-2">
                                                                啟用
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
</x-template-layout>

