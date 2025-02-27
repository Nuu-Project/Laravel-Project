<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <x-h.h3>檢舉類別管理</x-h.h3>

            <!-- 搜尋區塊在表格之前 -->
            <div class="mb-8">
                <x-div.flex-container>
                    <div class="p-4">
                        <a href="{{ route('admin.report-categories.create') }}"><x-button.search>新增標籤</x-button.search></a>
                    </div>
                    <div class="flex space-x-4">
                        <div class="flex items-center space-x-2">
                            <x-input.search type="text" name="filter[name]" placeholder="搜尋檢舉類別名稱...">
                            </x-input.search>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </div>
                    </div>
                </x-div.flex-container>
            </div>

            <!-- 表格區塊在搜尋區塊之後 -->
            <x-div.bg-white>
                <div class="overflow-x-auto">
                    <x-table.gray-200>
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    檢舉類別名稱
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    上傳時間
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    最後修改時間
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    操作
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    狀態
                                </th>
                            </tr>
                        </thead>
                        <x-gray-200>
                            <tr>
                                <x-gray-900>違規內容</x-gray-900>
                                <x-gray-900>2023/04/10</x-gray-900>
                                <x-gray-900>2023/07/05</x-gray-900>
                                <x-gray-900>
                                    <a href="{{ route('admin.report-categories.edit') }}">
                                        <x-button.blue-short>
                                            編輯
                                        </x-button.blue-short>
                                    </a>

                                    <a href="#" class="inline">
                                        <x-button.blue-short>
                                            啟用
                                        </x-button.blue-short>
                                    </a>

                                    <a href="#" class="inline">
                                        <x-button.red-short>
                                            刪除
                                        </x-button.red-short>
                                    </a>
                                </x-gray-900>
                                <x-gray-900>停用</x-gray-900>
                            </tr>
                        </x-gray-200>
                    </x-table.gray-200>
                </div>
                <x-div.bg-white>
                    <x-div.gray-200>
                    </x-div.gray-200>
                </x-div.bg-white>
            </x-div.bg-white>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
