<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'lg:hidden w-10 h-10 ml-auto flex items-center justify-center border border-blue-500 text-blue-500 rounded-md']) }}>
    {{ $slot }}
</button>
