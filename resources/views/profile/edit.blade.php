<x-template-layout>
    <x-slot name="header">
        {{ __('Profile') }}
    </x-slot>

    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.update-password-form')
        </div>
    </div>
</x-template-layout>
