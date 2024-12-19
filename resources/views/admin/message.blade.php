<x-template-admin-layout>
    <script src="{{ asset('js/admin/message/message.js') }}"></script>

    <!-- 主要內容 -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h3 class="text-gray-700 text-3xl font-medium mb-6">留言管理</h3>

            <!-- Reviews 搜索部分 -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 id="reviews-title" class="text-xl font-semibold text-gray-900">留言</h2>
                    <form action="{{ route('admin.messages.index') }}" method="GET">
                        <div>
                            <input type="text" name="filter[name]" id="filter[name]"
                                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="請輸入用戶名稱..." value="{{ request('filter.name') ?? '' }}">
                            <x-button.search>
                                搜索
                            </x-button.search>
                        </div>
                    </form>
                </div>

                <!-- Reviews 列表 -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg" id="reviews-table">
                    <div class="overflow-x-auto">
                        <x-table.message :messages="$messages->items()">
                        </x-table.message>
                    </div>

                    <!-- 分頁導航 -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-template-admin-layout>
