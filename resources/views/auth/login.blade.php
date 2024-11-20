<head>
    <x-head-layout />
</head>
<body class="font-body">
        <!-- home section -->
        <section class="bg-white py-10 md:mb-10">
            <div class="container max-w-screen-xl mx-auto px-4">
                <nav class="flex-wrap lg:flex items-center" x-data="{navbarOpen:false}">
                    <div class="flex items-center mb-10 lg:mb-0">
                        <img src="images/book-4-fix.png" alt="Logo">
                        <button class="lg:hidden w-10 h-10 ml-auto flex items-center justify-center border border-blue-500 text-blue-500 rounded-md" @click="navbarOpen = !navbarOpen">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                        </button>
                    </div>
                    <ul class="lg:flex flex-col lg:flex-row lg:items-center lg:mx-auto lg:space-x-8 xl:space-x-14" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
                        <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                            <a href="/">首頁</a>
                        </li>
                        <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                            <a href="{{ route('products.index') }}">商品</a>
                        </li>
                    </ul>
                    <div class="lg:flex flex-col md:flex-row md:items-center text-center md:space-x-6" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
                        @if (Route::has('register'))
                            <a href="/register" class="px-6 py-4 bg-blue-500 text-white font-semibold text-lg rounded-xl hover:bg-blue-700 transition ease-in-out duration-500 mb-5 md:mb-0">註冊</a>
                        @endif
                        @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none
                            focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">Dashboard</a>
                        @else
                            <a href="/login" class="px-6 py-4 border-2 border-blue-500 text-blue-500 font-semibold text-lg rounded-xl hover:bg-blue-700 hover:text-white transition ease-linear duration-500">登入</a>
                        @endif
                        @endauth
                    </div>
                </nav>
            </div>
        </section>

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <section class="mt-5">
    <div class="flex flex-col container max-w-screen-xl mx-auto px-4">
        <div class="flex items-center justify-center mb-6 text-2xl font-semibold text-gray-900 text-center">
          <img class="w-12 h-12 mr-2" src="images/sign.png" alt="logo">
          登入
            </div>
            @if ($errors->has('message'))
                <div class="text-sm text-red-600 space-y-1">
                    {!! $errors->first('message') !!}
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
            <label class="block mb-2 text-sm font-medium text-gray-900" for="email" :value="__('Email')">Your email</lable>
            <input class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="name@o365.nuu.edu.tw"> </input>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>


        <!-- Password -->
        <div class="mt-4">
            <label  class="block mb-2 text-sm font-medium text-gray-900" for="password" :value="__('Password')">Password</lable>

            <input id="password" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            type="password"
                            name="password"
                            required autocomplete="current-password"> </input>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>


        <!-- button -->
            <x-primary-button class="w-full text-black bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center border border-blue-500 flex justify-center">
                {{ __('Log in') }}
                </x-primary-button>


        <!-- Remember Me -->
        <div class="block mt-4">
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>


        <!-- Forgot your password -->
                @if (Route::has('password.request'))
                <a class="underline-none text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            </div>
        </div>
    </form>
  </div>
 </div>
</section>
</x-guest-layout>
