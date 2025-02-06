<div>
    <footer class="bg-white py-16">

        <x-div.container-screen>
            <div class="flex flex-col lg:flex-row lg:justify-between">

                <div class="space-y-7 mb-10 lg:mb-0">
                    <div class="flex justify-center lg:justify-start">
                        <img src="images/book-4-fix.png" alt="Image">
                    </div>
                    <p class="font-black text-gray-500 text-md md:text-xl mb-6 text-center lg:text-left">聯大二手書交易平台</p>
                </div>

                <div class="text-center lg:text-left space-y-7 mb-10 lg:mb-0">
                    <x-h.h4>STEP.1</x-h.h4>
                    <x-a.block-font-black href="/register">註冊帳戶</x-a.block-font-black>
                    <x-a.block-font-black href="/login">登入帳戶</x-a.block-font-black>
                </div>

                <div class="text-center lg:text-left space-y-7 mb-10 lg:mb-0">
                    <x-h.h4>STEP.2</x-h.h4>
                    <x-a.block-font-black href="{{ route('products.index') }}">點選菜單-商品</x-a.block-font-black>
                    <x-a.block-font-black href="{{ route('products.index') }}">瀏覽商品</x-a.block-font-black>
                </div>

                <div class="text-center lg:text-left space-y-7 mb-10 lg:mb-0">
                    <x-h.h4>STEP.3</x-h.h4>

                    <p
                        class="block font-black text-gray-800 text-sm md:text-lg hover:text-gray-1000 transition ease-in-out duration-300">
                        私訊賣家</p>

                    <p
                        class="block font-black text-gray-800 text-sm md:text-lg hover:text-gray-1000 transition ease-in-out duration-300">
                        完成交易</p>
                </div>

                <div class="text-center lg:text-left space-y-7 mb-10 lg:mb-0">
                    <x-h.h4>支持我們</x-h.h4>

                    <a class="block font-black text-gray-800 text-sm md:text-lg hover:text-gray-1000 transition ease-in-out duration-300"
                        href="https://docs.google.com/forms/d/e/1FAIpQLSfJP4fi8V-8nQ0UgMyFncuDxUhzdtvfWmI050Z6F5y73ndhug/viewform?embedded=true">
                        <p>
                            回饋表單</p>
                    </a>
                </div>

            </div>
        </x-div.container-screen>
    </footer>
</div>
