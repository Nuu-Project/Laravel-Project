<x-template-login-register-layout>

    <x-guest-layout>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <section class="mt-5">
            <x-div.container-screen>
                <div class="flex items-center justify-center mb-6 text-2xl font-semibold text-gray-900 text-center">
                    <img class="w-12 h-12 mr-2" src="images/sign.png" alt="logo">
                    登入
                </div>
                @if ($errors->has('message'))
                    <div class="text-sm text-red-600 space-y-1">
                        {!! $errors->first('message') !!}
                    </div>
                @endif
                @if ($errors->has('time_limit'))
                    <div class="text-sm text-red-600 space-y-1 text-center">
                        您的帳號已被暫時停用，<br>
                        請於 {{ $errors->first('time_limit') }} 後再嘗試。
                    </div>
                @endif
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl text-center">
                        Sign in to your account
                    </h1>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf


                        <!-- Email Address -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900" for="email"
                                :value="__('Email')">Your email</lable>
                                <input
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                    id="email" class="block mt-1 w-full" type="email" name="email"
                                    value="{{ old('email') }}" required autofocus placeholder="name@o365.nuu.edu.tw">
                                </input>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>


                        <!-- Password -->
                        <div class="mt-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900" for="password"
                                :value="__('Password')">Password</lable>

                                <input id="password"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                    type="password" name="password" required autocomplete="current-password"> </input>

                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>


                        <!-- button -->
                        <x-primary-button
                            class="w-full text-black bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center border border-blue-500 flex justify-center">
                            {{ __('Log in') }}
                        </x-primary-button>


                        <!-- Remember Me -->
                        <div class="block mt-4">
                            <div class="flex items-center justify-between">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        name="remember">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                </label>


                                <!-- Forgot your password -->
                                @if (Route::has('password.request'))
                                    <a class="underline-none text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </x-div.container-screen>
        </section>
    </x-guest-layout>
</x-template-login-register-layout>
