<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'absolute left-0 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-r']) }}>
    {{ $slot }}
</button>
