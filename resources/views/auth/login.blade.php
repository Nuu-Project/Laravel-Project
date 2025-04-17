<x-template-login-register-layout>
    <x-guest-layout>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <section class="mt-5">
            <x-div.container-screen>
                <x-div.image>
                    <img class="w-12 h-12 mr-2" src="images/sign.png" alt="logo">
                    登入
                </x-div.image>
                @if ($errors->has('message'))
                    <div class="text-sm text-red-600 space-y-1">
                        {!! $errors->first('message') !!}
                    </div>
                @endif
                @if ($errors->has('time_limit'))
                    @php
                        $user = \App\Models\User::where('email', old('email'))->first();
                    @endphp
                    <div class="text-sm text-red-600 space-y-1 text-center">
                        您的帳號已被暫時停用<br>
                        @if ($user && $user->suspend_reason)
                            原因：{{ $user->suspend_reason }}<br>
                        @endif
                        請於 {{ $errors->first('time_limit') }} 後再嘗試。
                    </div>
                @endif
                <div>
                    <x-h.h1>
                        Sign in to your account
                    </x-h.h1>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf


                        <!-- Email Address -->
                        <x-div.mt-4>
                            <x-input.auth-label for="email">{{ __('Your email') }}</x-input.auth-label>
                            <x-input.auth id="email" type="email" name="email" :value="old('email')" required
                                autofocus placeholder="name@o365.nuu.edu.tw" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </x-div.mt-4>


                        <!-- Password -->
                        <x-div.mt-4>
                            <x-input.auth-label for="password">{{ __('Password') }}</x-input.auth-label>
                            <x-input.auth id="password" type="password" name="password" required
                                autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </x-div.mt-4>


                        <!-- button -->
                        <x-primary-button>
                            {{ __('Log in') }}
                        </x-primary-button>


                        <!-- Remember Me -->
                        <x-div.mt-4>
                            <div class="flex items-center justify-between">
                                <x-input.checkbox-labeled id="remember_me" name="remember">
                                    {{ __('Remember me') }}
                                </x-input.checkbox-labeled>


                                <!-- Forgot your password -->
                                @if (Route::has('password.request'))
                                    <x-a.form-link href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </x-a.form-link>
                                @endif
                            </div>
                        </x-div.mt-4>
                    </form>
                </div>
            </x-div.container-screen>
        </section>
    </x-guest-layout>
</x-template-login-register-layout>
