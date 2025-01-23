<main
    {{ $attributes->merge(['type' => '', 'class' => 'flex-1 overflow-x-hidden overflow-y-auto bg-gray-200']) }}>
    {{ $slot }}
</main>
