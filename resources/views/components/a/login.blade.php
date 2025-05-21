<a
    {{ $attributes->merge(['type' => '', 'class' => 'inline-block px-4 sm:px-6 py-3 sm:py-4 border-2 border-blue-500 text-blue-500 font-semibold text-base sm:text-lg rounded-xl hover:bg-blue-700 hover:text-white transition ease-linear duration-500 w-full md:w-auto text-center']) }}>
    {{ $slot }}
</a>
