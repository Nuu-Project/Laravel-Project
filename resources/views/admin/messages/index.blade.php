<x-template-admin-layout>
    <script src="{{ asset('js/admin/message/message.js') }}"></script>

    <x-flex-container>
        <x-div.container>
            <x-h.h3>留言管理</x-h.h3>
            <div>
                <x-div.flex-container>
                    <x-h.h2 id="reviews-title">留言</x-h.h2>
                    <div>
                        <form action="{{ route('admin.messages.index') }}" method="GET">
                            <x-form.search-layout>
                                <x-responsive.container>
                                    <x-input.search type="text" name="filter[name]" placeholder="請輸入用戶名稱..."
                                        value="{{ request('filter.name') }}">
                                    </x-input.search>
                                </x-responsive.container>
                                <x-button.search>
                                    搜尋
                                </x-button.search>
                            </x-form.search-layout>
                        </form>
                    </div>
                </x-div.flex-container>

                <x-div.bg-white id="reviews-table">
                    <x-table.overflow-container>
                        <x-table.message :messages="$messages->items()">
                        </x-table.message>
                    </x-table.overflow-container>

                    <x-div.gray-200>
                        {{ $messages->links() }}
                    </x-div.gray-200>
                </x-div.bg-white>
            </div>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
