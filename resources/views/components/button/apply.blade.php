<button
    {{ $attributes->merge(['type' => 'button', 'id' => 'apply-tag-filters', 'class' => 'bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto']) }}>
    {{ $slot }}
</button>
