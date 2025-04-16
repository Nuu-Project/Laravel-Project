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
        <section class="mt-5">
            <x-div.container-screen>
                <x-div.image>
                    <img class="w-12 h-12 mr-2" src="images/sign-up.png" alt="logo">
                    註冊
                </x-div.image>
                <div>
                    <x-h.h1>
                        Create an account
                    </x-h.h1>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input.auth-label for="name">{{ __('Name') }}</x-input.auth-label>
                            <x-input.auth id="name" type="text" name="name" :value="old('name')" required
                                autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <x-div.mt-4>
                            <x-input.auth-label for="email">{{ __('Your email') }}</x-input.auth-label>
                            <x-input.auth id="email" type="email" name="email" :value="old('email')" required
                                placeholder="name@o365.nuu.edu.tw" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </x-div.mt-4>

                        <!-- Password -->
                        <x-div.mt-4>
                            <x-input.auth-label for="password">{{ __('Password') }}</x-input.auth-label>
                            <x-input.auth id="password" type="password" name="password" required
                                autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </x-div.mt-4>

                        <!-- Confirm Password -->
                        <x-div.mt-4>
                            <x-input.auth-label
                                for="password_confirmation">{{ __('Confirm Password') }}</x-input.auth-label>
                            <x-input.auth id="password_confirmation" type="password" name="password_confirmation"
                                required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </x-div.mt-4>

                        <x-primary-button>
                            {{ __('Register') }}
                        </x-primary-button>

                        <x-div.mt-4>
                            <x-a.form-link href="{{ route('login') }}">
                                {{ __('Already registered?') }}
                            </x-a.form-link>
                        </x-div.mt-4>
                    </form>
                </div>
            </x-div.container-screen>
        </section>
    </x-guest-layout>
</x-template-login-register-layout>
