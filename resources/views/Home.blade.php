<x-template-layout>

    <x-navbar />

    <!-- feature section -->
    <section class="bg-white py-1 md:mt-10">

        <div class="container max-w-screen-xl mx-auto px-4">

            <p class="font-black text-gray-500 text-lg md:text-xl text-center uppercase mb-6">平台理念</p>

            <h1 class="font-semibold text-gray-900 text-xl md:text-4xl text-center leading-normal mb-10">讓社恐和I人<br>
                都能有個安靜的空間買書</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-20">
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-20 py-6 flex justify-center bg-blue-200 bg-opacity-30 text-blue-700 rounded-xl">
                            <i data-feather="globe"></i>
                        </div>
                    </div>

                    <h4 class="font-semibold text-lg md:text-2xl text-gray-900 mb-6">簡單的操作介面</h4>

                    <p class="font-black text-gray-500 text-md md:text-xl mb-6">沒有繁瑣的流程，一切步驟都經過精簡</p>

                    <div class="flex justify-center">
                        </a>
                    </div>
                </div>

                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-20 py-6 flex justify-center bg-blue-200 bg-opacity-30 text-blue-700 rounded-xl">
                            <i data-feather="arrow-up-right"></i>
                        </div>
                    </div>

                    <h4 class="font-semibold text-lg md:text-2xl text-gray-900 mb-6">專為資管系服務的平台</h4>

                    <p class="font-black text-gray-500 text-md md:text-xl mb-6">在這裡刊登二手書的商品，都是資管系可能或必須用到的書</p>

                    <div class="flex justify-center">
                    </div>
                </div>

                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-20 py-6 flex justify-center bg-blue-200 bg-opacity-30 text-blue-700 rounded-xl">
                            <i data-feather="clock"></i>
                        </div>
                    </div>

                    <h4 class="font-semibold text-lg md:text-2xl text-gray-900 mb-6">方便省時</h4>

                    <p class="font-black text-gray-500 text-md md:text-xl mb-6">每個的人時間都很寶貴，我們想讓大家用最快的速度買到自己需要的教科書</p>

                    <div class="flex justify-center">

                    </div>
                </div>
            </div>

        </div> <!-- container.// -->

    </section>
    <!-- feature section //end -->

    <!-- donation section -->
    <x-product-card />
    <x-page-last />


    <script>
        feather.replace()
    </script>
</x-template-layout>
