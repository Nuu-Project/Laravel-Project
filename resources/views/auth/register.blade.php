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
            <section>
                <x-div.container-screen
                    class="border border-gray-300 rounded-xl shadow-lg p-8 max-w-md mx-auto bg-white/90">
                    <div class="flex justify-center items-center mb-1">
                        <x-img.icon src="images/sign-up.png" alt="logo" class="h-12 w-auto">
                            註冊
                        </x-img.icon>
                    </div>
                    <div>
                        <x-h.h1>
                            Create an account
                        </x-h.h1>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div>
                                <x-input-label for="name">{{ __('Name') }}</x-input-label>
                                <x-input.auth id="name" type="text" name="name" :value="old('name')" required
                                    autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <x-div.mt-4>
                                <x-input-label for="email">{{ __('email') }}</x-input-label>
                                <x-input.auth id="email" type="email" name="email" :value="old('email')" required
                                    placeholder="u1000000@o365.nuu.edu.tw" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </x-div.mt-4>

                            <x-div.mt-4>
                                <x-input-label for="password">{{ __('Password') }}</x-input-label>
                                <x-input.auth id="password" type="password" name="password" required
                                    autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </x-div.mt-4>

                            <x-div.mt-4>
                                <x-input-label for="password_confirmation">{{ __('Confirm Password') }}</x-input-label>
                                <x-input.auth id="password_confirmation" type="password" name="password_confirmation"
                                    required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </x-div.mt-4>

                            <x-div.mt-4>
                                <div class="flex items-center">
                                    <input id="terms" name="terms" type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        required>
                                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                                        我已閱讀並同意<a href="https://reurl.cc/YYb0gO"
                                            class="text-blue-600 hover:text-blue-800" target="_blank">使用規範</a>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('terms')" class="mt-2" />
                            </x-div.mt-4>

                            <x-primary-button>
                                {{ __('Register') }}
                            </x-primary-button>

                            <x-div.mt-4>
                                <div class="flex items-center justify-between">

                                    <x-a.form-link href="{{ route('register') }}">

                                    </x-a.form-link>

                                    <x-a.form-link href="{{ route('login') }}">
                                        {{ __('Already registered?') }}
                                    </x-a.form-link>
                                </div>
                            </x-div.mt-4>
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
