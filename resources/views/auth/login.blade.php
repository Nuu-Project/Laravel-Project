<x-template-login-register-layout>

    <!-- home section -->
    <section class="bg-white py-10 md:mb-10">
        <x-div.container-screen>
            <nav class="flex-wrap lg:flex items-center" x-data="{ navbarOpen: false }">
                <div class="flex items-center mb-10 lg:mb-0">
                    <img src="images/book-4-fix.png" alt="Logo">
                    <button
                        class="lg:hidden w-10 h-10 ml-auto flex items-center justify-center border border-blue-500 text-blue-500 rounded-md"
                        @click="navbarOpen = !navbarOpen">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-menu">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <ul class="lg:flex flex-col lg:flex-row lg:items-center lg:mx-auto lg:space-x-8 xl:space-x-14"
                    :class="{ 'hidden': !navbarOpen, 'flex': navbarOpen }">
                    <x-li.font-semibold><a href="{{ route('dashboard') }}">首頁</a></x-li.font-semibold>
                    <x-li.font-semibold><a href="{{ route('products.index') }}">商品</a></x-li.font-semibold>
                </ul>
                <div class="lg:flex flex-col md:flex-row md:items-center text-center md:space-x-6"
                    :class="{ 'hidden': !navbarOpen, 'flex': navbarOpen }">
                    @if (Route::has('register'))
                        <x-a.register href="/register">註冊</x-a.register>
                    @endif
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}">Dashboard</a>
                        @else
                            <x-a.login href="/login">登入</x-a.login>
                        @endif
                    @endauth
                </div>
            </nav>
        </x-div.container-screen>
    </section>

    <x-guest-layout>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <section class="mt-5">
            <x-div.container-screen>
                <x-div.image>
                    <x-img.icon src="images/sign.png" alt="logo">
                        登入
                    </x-img.icon>
                </x-div.image>
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
                <div>
                    <x-h.h1>
                        Sign in to your account
                    </x-h.h1>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf


                        <!-- Email Address -->
                        <x-div.mt-4>
                            <x-input-label for="email">{{ __('Your email') }}</x-input-label>
                            <x-input.auth id="email" type="email" name="email" :value="old('email')" required
                                autofocus placeholder="name@o365.nuu.edu.tw" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </x-div.mt-4>


                        <!-- Password -->
                        <x-div.mt-4>
                            <x-input-label for="password">{{ __('Password') }}</x-input-label>
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
