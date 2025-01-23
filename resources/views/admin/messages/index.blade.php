<x-template-admin-layout>
    <script src="{{ asset('js/admin/message/message.js') }}"></script>

    <!-- 主要內容 -->
    <x-main.flex-container>
        <x-div.container>
            <x-h.h3>留言管理</x-h.h3>
            <div class="mb-8">
                <x-div.flex-container>
                    <x-h.h2 id="reviews-title">留言</x-h.h2>
                    <form action="{{ route('admin.messages.index') }}" method="GET">
                        <div>
                            <x-input.search type="text" name="filter[name]" placeholder="請輸入用戶名稱..."
                                value="{{ request('filter.name') }}">
                            </x-input.search>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </div>
                    </form>
                </x-div.flex-container>
                <!-- Reviews 列表 -->
                <x-div.bg-white id="reviews-table">
                    <div class="overflow-x-auto">
                        <x-table.message :messages="$messages->items()">
                        </x-table.message>
                    </div>

                    <!-- 分頁導航 -->
                    <x-div.gray-200>
                        {{ $messages->links() }}
                    </x-div.gray-200>
                </x-div.bg-white>
            </div>
        </x-div.container>
    </x-main.flex-container>
</x-template-admin-layout>
