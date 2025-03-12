@php
    use App\Enums\ProductStatus;
@endphp

<x-template-admin-layout>
    <x-flex-container>
        <x-div.container>
            @if (session('success'))
                <x-div.green role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </x-div.green>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <x-h.h3 class="text-center sm:text-left">商品管理</x-h.h3>

            <!-- 搜尋區塊 -->
            <div class="mb-8">
                <x-div.flex-container class="flex-col sm:flex-row space-y-4 sm:space-y-0">
                    <x-h.h2 id="products-title" class="text-center sm:text-left">商品</x-h.h2>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                        <form action="{{ route('admin.products.index') }}" method="GET"
                            class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <x-input.search type="text" name="filter[name]" placeholder="搜尋商品名稱..."
                                value="{{ request('filter.name') }}" class="w-full sm:w-auto">
                            </x-input.search>
                            <x-input.search type="text" name="filter[user]" placeholder="搜尋賣家名稱..."
                                value="{{ request('filter.user') }}" class="w-full sm:w-auto">
                            </x-input.search>
                            <x-button.search class="w-full sm:w-auto">
                                搜尋
                            </x-button.search>
                        </form>
                    </div>
                </x-div.flex-container>
            </div>

            <x-div.bg-white>
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <x-table.gray-200 class="min-w-full table-auto">
                        <x-thead.products />
                        <x-gray-200>
                            @foreach ($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <x-gray-900 class="text-center sm:text-left whitespace-normal sm:whitespace-nowrap">{{ $product->id }}</x-gray-900>
                                    <x-gray-900 class="whitespace-normal">{{ $product->name }}</x-gray-900>
                                    <x-gray-900 class="whitespace-normal">{{ $product->user->name }}</x-gray-900>
                                    <x-gray-900>{{ $product->created_at->format('Y/m/d') }}</x-gray-900>
                                    <x-gray-900>{{ $product->updated_at->format('Y/m/d') }}</x-gray-900>
                                    <x-gray-900 class="text-center">{{ $product->reports_count }}</x-gray-900>
                                    <x-gray-900 class="space-y-2 sm:space-y-0 sm:space-x-2 flex flex-col sm:flex-row items-center justify-center">
                                        <a href="{{ route('products.show', ['product' => $product->id]) }}" class="w-full sm:w-auto">
                                            <x-button.blue-short class="w-full sm:w-auto">
                                                前往
                                            </x-button.blue-short>
                                        </a>

                                        <form
                                            action="{{ route('admin.products.inactive', ['product' => $product->id]) }}"
                                            method="POST" class="w-full sm:w-auto inline">
                                            @csrf
                                            @method('PUT')
                                            <x-button.status :status="$product->status" class="w-full sm:w-auto" />
                                        </form>

                                        <a href="{{ route('admin.reports.index', ['filter[reportable_id]' => $product->id]) }}"
                                            class="w-full sm:w-auto">
                                            <x-button.red-short class="w-full sm:w-auto">
                                                檢舉詳情
                                            </x-button.red-short>
                                        </a>
                                    </x-gray-900>
                                    <x-gray-900 class="text-center">{{ $product->status->name() }}</x-gray-900>
                                </tr>
                            @endforeach
                        </x-gray-200>
                    </x-table.gray-200>
                    <x-div.gray-200 class="px-4 sm:px-6">
                        {{ $products->links() }}
                    </x-div.gray-200>
                </div>
            </x-div.bg-white>
        </x-div.container>
    </x-flex-container>
</x-template-admin-layout>