<div>
    <footer class="bg-white py-16">

        <x-div.container-screen>
            <div class="flex flex-col lg:flex-row lg:justify-between">

                <div class="space-y-7 mb-10 lg:mb-0">
                    <div class="flex justify-center lg:justify-start">
                        <a href="#" onclick="$('html, body').animate({scrollTop: 0}, 100); return false;"
                            class="pointer">
                            <img src="images/book-4-fix.png" alt="Top" title="to Top">
                        </a>
                    </div>
                    <p class="font-black text-gray-500 text-md md:text-xl mb-6 text-center lg:text-left">聯大二手書交易平台</p>
                </div>

                <x-div.text-center>
                    <x-h.h4>STEP.1</x-h.h4>
                    <x-a.block-font-black href="/register">註冊帳戶</x-a.block-font-black>
                    <x-a.block-font-black href="/login">登入帳戶</x-a.block-font-black>
                </x-div.text-center>

                <x-div.text-center>
                    <x-h.h4>STEP.2</x-h.h4>
                    <x-a.block-font-black href="{{ route('products.index') }}">點選菜單-商品</x-a.block-font-black>
                    <x-a.block-font-black href="{{ route('products.index') }}">瀏覽商品</x-a.block-font-black>
                </x-div.text-center>

                <x-div.text-center>
                    <x-h.h4>STEP.3</x-h.h4>

                    <x-p.font-black>
                        私訊賣家</x-p.font-black>

                    <x-p.font-black>
                        完成交易</x-p.font-black>
                </x-div.text-center>

                <x-div.text-center>
                    <x-h.h4>支持我們</x-h.h4>

                    <x-a.block-font-black
                        href="https://docs.google.com/forms/d/e/1FAIpQLSfJP4fi8V-8nQ0UgMyFncuDxUhzdtvfWmI050Z6F5y73ndhug/viewform?embedded=true">
                        <p>
                            回饋表單</p>
                    </x-a.block-font-black>
                </x-div.text-center>

            </div>
        </x-div.container-screen>
    </footer>
</div>
