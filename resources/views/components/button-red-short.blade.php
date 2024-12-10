<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700']) }}>
    {{ $slot }}
</button>
