<x-template-admin-layout>

    <!-- 主要內容 -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <x-h.h3>檢舉詳情</x-h.h3>

            <div class="mb-8">
                <form action="{{ route('admin.reports.index') }}" method="GET">
                    <div class="flex items-center justify-between mb-4">
                        <x-h.h2 id="reviews-title">Reviews</x-h.h2>
                        <div>
                            <input type="text" id="filter[name]" name="filter[name]"
                                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="請輸入商品名稱..." value="{{ request('filter.name') }}">
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </div>
                    </div>
                </form>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
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
                        </table>
                    </div>

                    <!-- 分頁導航 -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $reportables->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-template-admin-layout>
