<div
    {{ $attributes->merge(['type' => '', 'class' => 'flex flex-col md:flex-row gap-2 md:gap-4 w-full px-4 md:px-0 mt-4 md:mt-0']) }}>
    {{ $slot }}
</div>
