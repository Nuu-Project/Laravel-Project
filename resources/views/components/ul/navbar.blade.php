<ul
    x-bind:class="{ 'hidden': !navbarOpen, 'flex': navbarOpen }"
    {{ $attributes->merge(['type' => '', 'class' => 'lg:flex flex-col lg:flex-row lg:items-center lg:mx-auto lg:space-x-8 xl:space-x-14']) }}>
    {{ $slot }}
</ul>
