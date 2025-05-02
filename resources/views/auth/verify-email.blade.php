<x-template-login-register-layout>
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="w-full max-w-md px-6 py-8 bg-white bg-opacity-90 rounded-lg shadow-md">
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>
            
            @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
            @endif

            <div class="mt-6 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-primary-button>
                        {{ __('重新寄一封驗證信') }}
                    </x-primary-button>
                </form>
                
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    {{ __('返回首頁') }}
                </a>
            </div>
        </div>
    </div>
</x-template-login-register-layout>