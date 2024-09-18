<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ __('新的驗證連結已傳送至郵箱') }}
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