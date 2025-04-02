@props(['reportables'])

<x-table.gray-200>
    <x-thead.products-reportable />
    <x-tbody>
        @foreach ($reportables as $reportable)
            <tr>
                <x-td>{{ $reportable->reportable ? $reportable->reportable->name : 'N/A' }}</x-td>
                <x-td>{{ $reportable->report->reportType->name }}</x-td>
                <x-td>{{ $reportable->report->description }}</x-td>
                <x-td>{{ $reportable->report->user->email }}</x-td>
                <x-td>{{ $reportable->report->updated_at->format('Y-m-d') }}</x-td>
            </tr>
        @endforeach
    </x-tbody>
</x-table.gray-200> 