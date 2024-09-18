<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    <div class="flex items-center justify-end mt-4">
        <a class="no-underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('dashboard') }}">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </a>
    </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('email/verification-notification') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('重新寄一封驗證信') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>