@props(['disabled' => false])

<label {{ $attributes->merge(['class' => 'inline-flex items-center']) }}>
    <input type="checkbox" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
        'class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500',
    ]) !!}>
    <span class="ms-2 text-sm text-gray-600">{{ $slot }}</span>
</label> 