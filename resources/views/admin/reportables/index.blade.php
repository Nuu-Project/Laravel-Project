<x-template-admin-layout>
    <script src="{{ asset('js/admin/reportables/reportables.js') }}"></script>

    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <x-h.h3>檢舉詳情</x-h.h3>

            <!-- 共用的頂部信息和標籤按鈕 -->
            <x-div.flex-container>
                <x-h.h2 id="reviews-title">Reviews</x-h.h2>
                <div class="flex items-center">
                    <!-- 標籤按鈕 -->
                    <div class="flex mr-4">
                        <button id="product-tab"
                            class="cat-tab px-4 py-2 mr-2 font-medium text-sm bg-white rounded-lg border border-gray-200 active"
                            onclick="switchTab('product')">商品檢舉詳情
                        </button>
                        <button id="message-tab"
                            class="cat-tab px-4 py-2 font-medium text-sm bg-gray-100 rounded-lg border border-gray-200"
                            onclick="switchTab('message')">留言檢舉詳情
                        </button>
                    </div>

                    <!-- 搜尋功能 - 商品 -->
                    <div id="product-search">
                        <form action="{{ route('admin.reportables.index') }}" method="GET" class="flex">
                            <x-input.search type="text" name="filter[name]" placeholder="搜尋商品名稱..."
                                value="{{ request('filter.name') }}">
                            </x-input.search>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </form>
                    </div>

                    <!-- 搜尋功能 - 留言 -->
                    <div id="message-search" class="hidden">
                        <form action="{{ route('admin.message-reportables.index') }}" method="GET" class="flex">
                            <x-input.search type="text" name="filter[name]" placeholder="搜尋留言內容..."
                                value="{{ request('filter.name') }}">
                            </x-input.search>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </form>
                    </div>
                </div>
            </x-div.flex-container>

            <!-- 商品檢舉詳情內容 -->
            <div id="product-content" class="mb-8 mt-4">
                <x-div.bg-white>
                    <div class="w-full">
                        <x-table.gray-200 class="w-full">
                            <x-thead.reportable />
                            <x-gray-200>
                                @foreach ($reportables as $reportable)
                                    <tr>
                                        <x-gray-900>{{ $reportable->reportable ? $reportable->reportable->name : 'N/A' }}</x-gray-900>
                                        <x-gray-900>{{ json_decode($reportable->report->reportType->name, true)['zh_TW'] }}</x-gray-900>
                                        <x-gray-900>{{ $reportable->report->description }}</x-gray-900>
                                        <x-gray-900>{{ $reportable->report->user->email }}</x-gray-900>
                                        <x-gray-900>{{ $reportable->report->updated_at->format('Y-m-d') }}</x-gray-900>
                                    </tr>
                                @endforeach
                            </x-gray-200>
                        </x-table.gray-200>
                    </div>

                    <!-- 分頁導航 -->
                    <x-div.gray-200>
                        {{ $reportables->links() }}
                    </x-div.gray-200>
                </x-div.bg-white>
            </div>

            <!-- 留言檢舉詳情內容 -->
            <div id="message-content" class="mb-8 mt-4 hidden">
                <x-div.bg-white>
                    <div class="w-full">
                        <x-table.gray-200 class="w-full">
                            <x-thead.reportable />
                            <x-gray-200>
                                @if (isset($messageReportables))
                                    @foreach ($messageReportables as $reportable)
                                        <tr>
                                            <x-gray-900>{{ $reportable->reportable ? $reportable->reportable->name : 'N/A' }}</x-gray-900>
                                            <x-gray-900>{{ json_decode($reportable->report->reportType->name, true)['zh_TW'] }}</x-gray-900>
                                            <x-gray-900>{{ $reportable->report->description }}</x-gray-900>
                                            <x-gray-900>{{ $reportable->report->user->email }}</x-gray-900>
                                            <x-gray-900>{{ $reportable->report->updated_at->format('Y-m-d') }}</x-gray-900>
                                        </tr>
                                    @endforeach
                                @endif
                            </x-gray-200>
                        </x-table.gray-200>
                    </div>

                    <!-- 分頁導航 -->
                    <x-div.gray-200>
                        @if (isset($messageReportables))
                            {{ $messageReportables->links() }}
                        @endif
                    </x-div.gray-200>
                </x-div.bg-white>
            </div>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
