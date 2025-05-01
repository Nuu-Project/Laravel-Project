<x-template-login-register-layout>
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="w-full max-w-md px-6 py-8 bg-white bg-opacity-90 rounded-lg shadow-md">
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-input.auth id="email" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <x-a.form-link href="{{ route('login') }}">
                    {{ __('返回登入頁面') }}
                </x-a.form-link>
            </div>
        </div>
    </div>
</x-template-login-register-layout>
