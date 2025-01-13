@php
    use App\Enums\ProductStatus;
@endphp

<x-template-admin-layout>

    <!-- 主要內容 -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <x-table.products />
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
        </div>
    </main>
</x-template-admin-layout>
