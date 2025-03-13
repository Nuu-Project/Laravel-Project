<select
    {{ $attributes->merge(['type' => '', 'class' => 'bg-gray text-primary-foreground px-4 py-2 rounded-md']) }}>
    {{ $slot }}
</select> 