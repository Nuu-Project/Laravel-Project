@php
    use App\Enums\ProductStatus;
@endphp

<x-template-admin-layout>

    <!-- 主要內容 -->
    <x-main.flex-container>
        <x-div.container>
            {{-- 添加提示訊息顯示 --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <x-h.h3>商品管理</x-h.h3>

            <!-- 新增搜尋區塊 -->
            <div class="mb-8">
                <x-div.flex-container>
                    <x-h.h2 id="products-title">商品</x-h.h2>
                    <div class="flex space-x-4">
                        <form action="{{ route('admin.products.index') }}" method="GET"
                            class="flex items-center space-x-2">
                            <input type="text" name="filter[name]" placeholder="搜尋商品名稱..."
                                value="{{ request('filter.name') }}"
                                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <x-button.search>搜尋</x-button.search>
                        </form>
                    </div>
                </x-div.flex-container>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <x-thead.products />
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($products as $product)
                                <tr>
                                    <x-td.gray-900>{{ $product->id }}</x-td.gray-900>
                                    <x-td.gray-900>{{ $product->name }}</x-td.gray-900>
                                    <x-td.gray-500>{{ $product->user->name }}</x-td.gray-500>
                                    <x-td.gray-500>{{ $product->created_at->format('Y/m/d') }}</x-td.gray-500>
                                    <x-td.gray-500>{{ $product->updated_at->format('Y/m/d') }}</x-td.gray-500>
                                    <x-td.gray-500>{{ $product->reports_count }}</x-td.gray-500>
                                    <x-td.operate>
                                        <a href="{{ route('products.show', ['product' => $product->id]) }}">
                                            <x-button.blue-short>
                                                前往
                                            </x-button.blue-short>
                                        </a>

                                        <form
                                            action="{{ route('admin.products.inactive', ['product' => $product->id]) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <x-button.status :status="$product->status" />
                                        </form>

                                        <a
                                            href="{{ route('admin.reports.index', ['filter[reportable_id]' => $product->id]) }}">
                                            <x-button.red-short>
                                                檢舉詳情
                                            </x-button.red-short>
                                        </a>
                                        <x-td.gray-500>{{ $product->status->name() }}</x-td.gray-500>
                                    </x-td.operate>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </x-div.container>
    </x-main.flex-container>
</x-template-admin-layout>
