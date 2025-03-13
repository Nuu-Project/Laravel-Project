<x-template-admin-layout>
    <!-- 主要內容 -->
    <x-flex-container>
        <x-div.container>
            <x-h.h3>檢舉詳情</x-h.h3>

            <div class="mb-8">
                <x-div.flex-container>
                    <x-h.h2 id="reviews-title">Reviews</x-h.h2>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                        <form action="{{ route('admin.reports.index') }}" method="GET"
                            class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <div class="w-full sm:w-auto">
                                <x-select.filter name="filter[type]">
                                    <option value="{{ App\Enums\ReportType::Product->value() }}"
                                        {{ request('filter.type') === App\Enums\ReportType::Product->value() ? 'selected' : '' }}>
                                        商品</option>
                                    <option value="{{ App\Enums\ReportType::Message->value() }}"
                                        {{ request('filter.type') === App\Enums\ReportType::Message->value() ? 'selected' : '' }}>
                                        留言</option>
                                </x-select.filter>
                            </div>
                            <div class="w-full sm:w-auto">
                                <x-input.search type="text" name="filter[name]" placeholder="搜尋商品名稱或訊息..."
                                    value="{{ request('filter.name') }}">
                                </x-input.search>
                            </div>
                            <x-button.search>
                                搜尋
                            </x-button.search>
                        </form>
                    </div>
                </x-div.flex-container>
            </div>

            <!-- 商品檢舉詳情內容 -->
            <x-div.content-section id="product-content">
                <x-div.bg-white>
                    <x-div.overflow-x>
                        <x-table.gray-200>
                            <x-thead.reportable />
                            <x-gray-200>
                                @foreach ($reportables as $reportable)
                                    <x-tr.hover>
                                        <x-gray-900>{{ $reportable->reportable ? $reportable->reportable->name : 'N/A' }}</x-gray-900>
                                        <x-gray-900>{{ $reportable->report->reportType->name }}</x-gray-900>
                                        <x-gray-900>{{ $reportable->report->description }}</x-gray-900>
                                        <x-gray-900>{{ $reportable->report->user->email }}</x-gray-900>
                                        <x-gray-900>{{ $reportable->report->updated_at->format('Y-m-d') }}</x-gray-900>
                                    </x-tr.hover>
                                @endforeach
                            </x-gray-200>
                        </x-table.gray-200>
                    </x-div.overflow-x>

                    <!-- 分頁導航 -->
                    <x-div.gray-200>
                        {{ $reportables->links() }}
                    </x-div.gray-200>
                </x-div.bg-white>
            </x-div.content-section>

            <!-- 留言檢舉詳情內容 -->
            <x-div.content-section id="message-content" class="hidden">
                <x-div.bg-white>
                    <x-div.overflow-x>
                        <x-table.gray-200>
                            <x-thead.reportable />
                            <x-gray-200>
                                @if (isset($messageReportables))
                                    @foreach ($messageReportables as $reportable)
                                        <x-tr.hover>
                                            <x-gray-900></x-gray-900>
                                            <x-gray-900></x-gray-900>
                                            <x-gray-900></x-gray-900>
                                            <x-gray-900></x-gray-900>
                                            <x-gray-900></x-gray-900>
                                        </x-tr.hover>
                                    @endforeach
                                @endif
                            </x-gray-200>
                        </x-table.gray-200>
                    </x-div.overflow-x>

                    <!-- 分頁導航 -->
                    <x-div.gray-200>
                        @if (isset($messageReportables))
                            {{ $messageReportables->links() }}
                        @endif
                    </x-div.gray-200>
                </x-div.bg-white>
            </x-div.content-section>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>