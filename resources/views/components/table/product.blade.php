@php
    use App\Enums\ReportType;
@endphp

@props(['products'])

<x-table.gray-200 class="min-w-full table-fixed whitespace-nowrap">
    <x-thead.products />
    <x-tbody>
        @foreach ($products as $product)
            <tr class="hover:bg-gray-50">
                <x-td>{{ $product->id }}</x-td>
                <x-td>{{ $product->name }}</x-td>
                <x-td>{{ $product->user->name }}</x-td>
                <x-td>{{ $product->created_at->format('Y/m/d') }}</x-td>
                <x-td>{{ $product->updated_at->format('Y/m/d') }}</x-td>
                <x-td>{{ $product->reports_count }}</x-td>
                <x-td>
                    <div class="flex space-x-2">
                        <a href="{{ route('products.show', ['product' => $product->id]) }}">
                            <x-button.blue-short>
                                前往
                            </x-button.blue-short>
                        </a>

                        <form action="{{ route('admin.products.inactive', ['product' => $product->id]) }}" method="POST"
                            class="inline">
                            @csrf
                            @method('PATCH')
                            <x-button.status :status="$product->status" />
                        </form>

                        <a
                            href="{{ route('admin.reports.index', ['filter[reportable_id]' => $product->id, 'filter[type]' => ReportType::Product->value]) }}">
                            <x-button.red-short>
                                檢舉詳情
                            </x-button.red-short>
                        </a>
                    </div>
                </x-td>
                <x-td>{{ $product->status->name() }}</x-td>
            </tr>
        @endforeach
    </x-tbody>
</x-table.gray-200>
