<div
    {{ $attributes->merge(['type' => 'button', 'class' => 'tag-selector-button w-full text-left p-3 bg-white rounded-md flex justify-between items-center border border-gray-300 hover:border-gray-400']) }}>
    {{ $slot }}
</div>
