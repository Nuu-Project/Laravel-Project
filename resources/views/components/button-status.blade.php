@props(['status'])

@php
    $buttonClass = $status === \App\Enums\ProductStatus::Active
        ? 'bg-red-600 hover:bg-red-700'
        : 'bg-blue-600 hover:bg-blue-700';
@endphp

<button {{ $attributes->merge(['class' => "px-3 py-1 {$buttonClass} text-white rounded"]) }}>
    {{ $status === \App\Enums\ProductStatus::Active ? '下架' : '上架' }}
</button>
