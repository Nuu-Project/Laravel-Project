<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex items-center justify-center whitespace-nowrap rounded-none text-lg font-semibold ring-offset-background transition-colors ease-in-out duration-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-blue-500 text-white hover:bg-blue-700 h-11 px-8']) }}>
    {{ $slot }}
</button>
