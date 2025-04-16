@props(['disabled' => false])

<button {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'w-full text-black bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center border border-blue-500 flex justify-center mt-4',
]) }}>
    {{ $slot }}
</button>
