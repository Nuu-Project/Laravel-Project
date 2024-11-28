<x-template-layout>


    <body class="font-body">
        <div class="flex flex-col md:flex-row h-screen bg-gray-100">
            <x-side-bar />

            <!-- 主要內容區 -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <x-navbar-admin />

                <!-- 主要內容 -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        <h3 class="text-gray-700 text-3xl font-medium mb-6">檢舉詳情</h3>

                        <!-- Reviews 搜索部分 -->
                        <div class="mb-8">
                            <form action="{{ route('admin.reports.index') }}" method="GET">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 id="reviews-title" class="text-xl font-semibold text-gray-900">Reviews</h2>
                                    <div>
                                        <label for="filter[reportable_id]" class="sr-only">搜索留言</label>
                                        <input type="text" id="filter[reportable_id]" name="filter[reportable_id]"
                                            class="w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm"
                                            placeholder="請輸入查詢ID..." value="{{ request('filter.reportable_id') }}">
                                    </div>
                                </div>
                            </form>

                            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    商品名稱</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    檢舉原因</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    自定義原因</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    檢舉人</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    檢舉日期</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($reportables as $reportable)
                                                <tr>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $reportable->reportable->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ json_decode($reportable->report->name, true)['zh'] }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $reportable->description }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $reportable->whistleblower->email }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $reportable->updated_at->format('Y-m-d') }}
                                                    </td>
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
            </div>
        </div>
</x-template-layout>
