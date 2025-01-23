<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-main.flex-container>
        <x-div.container>
            <x-h.h3>檢舉詳情</x-h.h3>

            <div class="mb-8">
                <form action="{{ route('admin.reports.index') }}" method="GET">
                    <x-div.flex-container>
                        <x-h.h2 id="reviews-title">Reviews</x-h.h2>
                        <div>
                            <x-input.search type="text" name="filter[name]" placeholder="搜尋商品名稱..."
                                value="{{ request('filter.name') }}">
                            </x-input.search>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </div>
                    </x-div.flex-container>
                </form>

                <x-div.bg-white>>
                    <div class="overflow-x-auto">
                        <x-table.gray-200>
                            <x-thead.report />
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($reportables as $reportable)
                                    <tr>
                                        <x-td.gray-900>{{ $reportable->reportable->name }}</x-td.gray-900>
                                        <x-td.gray-500>{{ json_decode($reportable->report->name, true)['zh_TW'] }}</x-td.gray-500>
                                        <x-td.gray-500>{{ $reportable->description }}</x-td.gray-500>
                                        <x-td.gray-500>{{ $reportable->whistleblower->email }}</x-td.gray-500>
                                        <x-td.gray-500>{{ $reportable->updated_at->format('Y-m-d') }}</x-td.gray-500>
                                    </tr>
                                @endforeach
                            </tbody>
                        </x-table.gray-200>
                    </div>

                    <!-- 分頁導航 -->
                    <x-div.gray-200>
                        {{ $reportables->links() }}
                    </x-div.gray-200>
                </x-div.bg-white>
            </div>
        </x-div.container>
    </x-main.flex-container>
</x-template-admin-layout>
