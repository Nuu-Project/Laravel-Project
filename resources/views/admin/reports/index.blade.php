@php
    use App\Enums\ReportType;
@endphp

<x-template-admin-layout>
    <x-flex-container>
        <x-div.container>
            <x-h.h3>檢舉詳情</x-h.h3>

            <div>
                <x-div.flex-container>
                    <x-h.h2 id="reviews-title">Reviews</x-h.h2>
                    <div>
                        <form action="{{ route('admin.reports.index') }}" method="GET">
                            <x-form.search-layout>
                                <x-responsive.container>
                                    <x-input.select name="filter[type]">
                                        @foreach (ReportType::cases() as $reportType)
                                            <option value="{{ $reportType->value }}"
                                                {{ request('filter.type') == $reportType->value ? 'selected' : '' }}>
                                                {{ $reportType }}
                                            </option>
                                        @endforeach
                                    </x-input.select>
                                </x-responsive.container>
                                <x-responsive.container>
                                    <x-input.search type="text" name="filter[name]" placeholder="搜尋商品名稱或訊息..."
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
            </div>

            @if (request('filter.type') === ReportType::Product->value())
                <div id="product-content">
                    <x-div.bg-white>
                        <x-table.overflow-container>
                            <x-table.report-product :reportables="$reportables" />
                        </x-table.overflow-container>

                        <x-div.gray-200>
                            {{ $reportables->links() }}
                        </x-div.gray-200>
                    </x-div.bg-white>
                </div>
            @endif

            @if (request('filter.type') === ReportType::Message->value())
                <div id="message-content">
                    <x-div.bg-white>
                        <x-table.overflow-container>
                            <x-table.report-message :reportables="$reportables" />
                        </x-table.overflow-container>

                        <x-div.gray-200>
                            {{ $reportables->links() }}
                        </x-div.gray-200>
                    </x-div.bg-white>
                </div>
            @endif
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
