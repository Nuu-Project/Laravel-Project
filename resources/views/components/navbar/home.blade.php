    <section>

        <x-div.container-screen>

            <nav class="flex-wrap lg:flex items-center" x-data="{ navbarOpen: false }">
                <div class="flex items-center mb-10 lg:mb-0">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/book-4-fix.png') }}" alt="Logo" class="w-24 md:w-32 lg:w-40">
                    </a>

                    <x-button.svg @click="navbarOpen = !navbarOpen">
                        <i data-feather="menu"></i>
                    </x-button.svg>
                </div>



                @auth
                    <ul class="lg:flex flex-col lg:flex-row lg:items-center lg:mx-auto lg:space-x-8 xl:space-x-14"
                        :class="{ 'hidden': !navbarOpen, 'flex': navbarOpen }">
                        <x-li.font-semibold><a href="{{ route('dashboard') }}">首頁</a></x-li.font-semibold>
                        <x-li.font-semibold><a href="{{ route('products.index') }}">商品</a></x-li.font-semibold>
                    </ul>
                @else
                    <ul class="lg:flex flex-col lg:flex-row lg:items-center lg:mx-auto lg:space-x-8 xl:space-x-14"
                        :class="{ 'hidden': !navbarOpen, 'flex': navbarOpen }">
                        <x-li.font-semibold><a href="{{ route('dashboard') }}">首頁</a></x-li.font-semibold>
                        <x-li.font-semibold><a href="{{ route('products.index') }}">商品</a></x-li.font-semibold>
                    </ul>
                @endauth

                <div class="lg:flex flex-col md:flex-row md:items-center"
                    :class="{ 'hidden': !navbarOpen, 'flex': navbarOpen }">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-3xl leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <img width="65" height="65" src="{{ asset('images/account.png') }}"
                                        alt="">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('user.profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('user.products.index')">
                                    {{ __('使用者後台') }}
                                </x-dropdown-link>

                                @role('admin')
                                    <x-dropdown-link :href="route('admin.messages.index')">
                                        {{ __('管理者後台') }}
                                    </x-dropdown-link>
                                @endrole

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <x-div.navbar>
                            <x-a.register href="{{ route('register') }}">註冊</x-a.register>
                            <x-a.login href="{{ route('login') }}">登入</x-a.login>
                        </x-div.navbar>
                    @endauth
                </div>
            </nav>
        </x-div.container-screen>
    </section>
