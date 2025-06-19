<button
    {{ $attributes->merge(['type' => 'button', 'id' => 'confirm-tag-selection', 'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto']) }}>
    {{ $slot }}
</button>
