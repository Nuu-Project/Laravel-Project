<table
    {{ $attributes->merge(['type' => '', 'class' => 'min-w-full divide-y divide-gray-200 table-auto']) }}>
    {{ $slot }}
</table>
