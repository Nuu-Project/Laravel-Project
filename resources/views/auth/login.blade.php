<x-template-login-register-layout>
    <div class="min-h-screen flex flex-col">
        <section>
            <x-div.container-screen>
                <nav class="flex-wrap lg:flex items-center" x-data="{ navbarOpen: false }">
                    <div class="flex items-center mb-10 lg:mb-0">
                        <button
                            class="lg:hidden w-10 h-10 ml-auto flex items-center justify-center border border-blue-500 text-blue-500 rounded-md"
                            @click="navbarOpen = !navbarOpen">
                            <i data-feather="menu"></i>
                        </button>
                    </div>

                    <ul class="lg:flex flex-col lg:flex-row lg:items-center lg:mx-auto lg:space-x-8 xl:space-x-14"
                        :class="{ 'hidden': !navbarOpen, 'flex': navbarOpen }">
                        <x-li.font-semibold><a href="{{ route('dashboard') }}">首頁</a></x-li.font-semibold>
                        <x-li.font-semibold><a href="{{ route('products.index') }}">商品</a></x-li.font-semibold>
                    </ul>

                    <div class="lg:flex flex-col md:flex-row md:items-center text-center md:space-x-6"
                        :class="{ 'hidden': !navbarOpen, 'flex': navbarOpen }">
                    </div>
                </nav>
            </x-div.container-screen>
        </section>

        <div class="flex-1 flex items-center justify-center">
            <x-auth-session-status class="mb-2" :status="session('status')" />
            <section>
                <x-div.container-screen>
                    <div class="flex justify-center items-center mb-1">
                        <x-img.icon src="images/book-4-fix.png" alt="logo" class="h-12 w-auto">
                            登入
                        </x-img.icon>
                    </div>
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
                            <x-div.mt-4>
                                <x-input-label for="email">{{ __('Your email') }}</x-input-label>
                                <x-input.auth id="email" type="email" name="email" :value="old('email')" required
                                    autofocus placeholder="name@o365.nuu.edu.tw" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </x-div.mt-4>

                            <x-div.mt-4>
                                <x-input-label for="password">{{ __('Password') }}</x-input-label>
                                <x-input.auth id="password" type="password" name="password" required
                                    autocomplete="current-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </x-div.mt-4>

                            <x-primary-button>
                                {{ __('Log in') }}
                            </x-primary-button>

                            <div class="flex items-center justify-between mt-4">
                                <x-input.checkbox-labeled id="remember_me" name="remember">
                                    {{ __('Remember me') }}
                                </x-input.checkbox-labeled>

                                <div class="flex flex-col items-end space-y-2">
                                    @if (Route::has('password.request'))
                                        <x-a.form-link href="{{ route('password.request') }}">
                                            {{ __('Forgot your password?') }}
                                        </x-a.form-link>
                                    @endif

                                    <x-a.form-link href="{{ route('register') }}">
                                        {{ __('Have not registered?') }}
                                    </x-a.form-link>
                                </div>
                            </div>
                        </form>
                    </div>
                </x-div.container-screen>
            </section>
        </div>
    </div>
    <script>
        feather.replace()
    </script>
</x-template-login-register-layout>
