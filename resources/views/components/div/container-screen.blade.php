@props(['mode' => 'default'])

<div
    {{ $attributes->merge([
        'type' => '',
        'class' =>
            $mode === 'card'
                ? 'container max-w-screen-xl mx-auto px-4 border border-gray-300 rounded-xl shadow-lg p-8 max-w-md mx-auto bg-white/90'
                : 'container max-w-screen-xl mx-auto px-4',
    ]) }}>
    {{ $slot }}
</div>
