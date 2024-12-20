<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300 ease-in-out']) }}>
    {{ $slot }}
</button>
