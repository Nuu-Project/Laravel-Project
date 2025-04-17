<x-template-layout>

    <section class="bg-white py-1 md:mt-10">

        <x-div.container-screen>

            <p class="font-black text-gray-500 text-md md:text-xl text-center uppercase mb-6">平台理念</p>

            <x-h.h1>讓社恐和I人<br>都能有個安靜的空間買書</x-h.h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-20">
                <x-div.center>
                    <x-div.justify>
                        <x-div.opacity>
                            <i data-feather="globe"></i>
                        </x-div.opacity>
                    </x-div.justify>

                    <x-h.h4>簡單的操作介面</x-h.h4>

                    <x-p.font-black>沒有繁瑣的流程，一切步驟都經過精簡</x-p.font-black>

                    <x-div.justify>
                        </a>
                    </x-div.justify>
                </x-div.center>

                <x-div.center>
                    <x-div.justify>
                        <x-div.opacity>
                            <i data-feather="arrow-up-right"></i>
                        </x-div.opacity>
                    </x-div.justify>

                    <x-h.h4>專為聯大服務的平台</x-h.h4>

                    <x-p.font-black>在這裡刊登二手書的商品，都是可能或必須用到的書</x-p.font-black>

                    <x-div.justify>

                    </x-div.justify>
                </x-div.center>

                <x-div.center>
                    <x-div.justify>
                        <x-div.opacity>
                            <i data-feather="clock"></i>
                        </x-div.opacity>
                    </x-div.justify>

                    <x-h.h4>方便省時</x-h.h4>

                    <x-p.font-black>每個的人時間都很寶貴，我們想讓大家用最快的速度買到自己需要的教科書</x-p.font-black>

                    <x-div.justify>

                    </x-div.justify>
                </x-div.center>
            </div>

        </x-div.container-screen>

    </section>

    <x-product.card :products="$products"  />
    <x-page.last />


    <script>
        feather.replace()
    </script>
</x-template-layout>
