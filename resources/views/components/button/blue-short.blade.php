<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 w-full sm:w-auto']) }}>
    {{ $slot }}
</button>
