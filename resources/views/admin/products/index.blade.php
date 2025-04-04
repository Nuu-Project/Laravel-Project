@php
    use App\Enums\ProductStatus;
@endphp

<x-template-admin-layout>
    <x-flex-container>
        <x-div.container>
            @if (session('success'))
                <x-div.green role="alert">
                    <x-span.block-inline>{{ session('success') }}</x-span.block-inline>
                </x-div.green>
            @endif

            @if (session('error'))
                <x-div.red role="alert">
                    <x-span.block-inline>{{ session('error') }}</x-span.block-inline>
                </x-div.red>
            @endif

            <x-h.h3>商品管理</x-h.h3>

            <div>
                <x-div.flex-container>
                    <x-h.h2 id="products-title">商品</x-h.h2>
                    <div>
                        <form action="{{ route('admin.products.index') }}" method="GET">
                            <x-form.search-layout>
                                <x-input.search type="text" name="filter[name]" placeholder="搜尋商品名稱..."
                                    value="{{ request('filter.name') }}">
                                </x-input.search>
                                <x-input.search type="text" name="filter[user]" placeholder="搜尋賣家名稱..."
                                    value="{{ request('filter.user') }}">
                                </x-input.search>
                                <x-button.search>
                                    搜尋
                                </x-button.search>
                            </x-form.search-layout>
                        </form>
                    </div>
                </x-div.flex-container>
            </div>

            <x-div.bg-white>
                <x-table.overflow-container>
                    <x-table.product :products="$products" />
                </x-table.overflow-container>
                <x-div.gray-200>
                    {{ $products->links() }}
                </x-div.gray-200>
            </x-div.bg-white>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>
