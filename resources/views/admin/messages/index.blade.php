<x-template-admin-layout>
    <script src="{{ asset('js/admin/message/message.js') }}"></script>

    <x-flex-container>
        <x-div.container>
            <x-h.h3>留言管理</x-h.h3>
            <div class="mb-8">
                <x-div.flex-container>
                    <x-h.h2 id="reviews-title">留言</x-h.h2>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                        <form action="{{ route('admin.messages.index') }}" method="GET"
                            class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <div class="w-full sm:w-auto">
                                <x-input.search type="text" name="filter[name]" placeholder="請輸入用戶名稱..."
                                    value="{{ request('filter.name') }}" class="w-full">
                                </x-input.search>
                            </div>
                            <x-button.search class="w-full sm:w-auto">
                                搜尋
                            </x-button.search>
                        </form>
                    </div>
                </x-div.flex-container>

                <x-div.bg-white id="reviews-table">
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <x-table.message :messages="$messages->items()">
                        </x-table.message>
                    </div>

                    <x-div.gray-200>
                        {{ $messages->links() }}
                    </x-div.gray-200>
                </x-div.bg-white>
            </div>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
