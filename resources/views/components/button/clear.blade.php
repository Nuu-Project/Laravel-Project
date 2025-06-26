<button
    {{ $attributes->merge(['type' => 'button', 'id' => 'clear-tag-selection', 'class' => 'bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto']) }}>
    {{ $slot }}
</button>
