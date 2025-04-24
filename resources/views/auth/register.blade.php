<x-template-login-register-layout>
    <div class="flex justify-center py-6">
        <nav>
            <ul class="flex items-center space-x-8 xl:space-x-14">
                <x-li.font-semibold><a href="{{ route('dashboard') }}">首頁</a></x-li.font-semibold>
                <x-li.font-semibold><a href="{{ route('products.index') }}">商品</a></x-li.font-semibold>
            </ul>
        </nav>
    </div>

    <x-guest-layout>
        <section class="mt-5">
            <x-div.container-screen>
                <x-div.image>
                    <x-img.icon src="images/sign-up.png" alt="logo">
                        註冊
                    </x-img.icon>
                </x-div.image>
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
                                placeholder="name@o365.nuu.edu.tw" />
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
    </x-guest-layout>
</x-template-login-register-layout>
