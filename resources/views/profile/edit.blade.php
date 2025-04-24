<x-template-layout>
    <x-slot name="header">
        {{ __('Profile') }}
    </x-slot>

        <div>
            @include('profile.update-password-form')
        </div>
</x-template-layout>
