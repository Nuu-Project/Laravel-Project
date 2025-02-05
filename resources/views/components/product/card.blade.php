<div>
    <section class="bg-white py-16">

        <x-div.container-screen>

            <x-h.h1>推薦書籍</x-h.h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <x-div.card-box>
                    <img src="images/book-5.jpg" alt="Image"
                        class="mb-6 hover:opacity-75 transition ease-in-out duration-500">

                    <x-h.h4>統計學 <br></x-h.h4>


                    <div class="flex items-center justify-between mb-8">
                        <x-h.h6>年級 : <span class="font-semibold text-gray-900 text-md md:text-lg">大二【上下】</span></x-h.h6>

                        <x-h.h6>課程 : <span class="font-semibold text-gray-900 text-md md:text-lg">必修</span></x-h.h6>
                </x-div.card-box>


                <a href="{{ route('products.index', ['tags' => ['statistics']]) }}"
                    class="px-6 py-4 bg-info font-semibold text-white text-lg rounded-xl hover:bg-blue-700 transition ease-in-out duration-500">前往了解</a>
            </div>

            <x-div.card-box>
                <img src="images/book-6.jpg" alt="Image"
                    class="mb-6 hover:opacity-75 transition ease-in-out duration-500">

                <x-h.h4>會計學<br></x-h.h4>

                <div class="flex items-center justify-between mb-8">
                    <x-h.h6>年級 : <span class="font-semibold text-gray-900 text-md md:text-lg">大一【上】</span></x-h.h6>

                    <x-h.h6>課程 : <span class="font-semibold text-gray-900 text-md md:text-lg">必修</span></x-h.h6>
            </x-div.card-box>


            <a href="{{ route('products.index', ['tags' => ['accounting']]) }}"
                class="px-6 py-4 bg-info font-semibold text-white text-lg rounded-xl hover:bg-blue-700 transition ease-in-out duration-500">前往了解</a>
        </x-div.container-screen>

        <x-div.card-box>
            <img src="images/book-7.jpg" alt="Image"
                class="mb-6 hover:opacity-75 transition ease-in-out duration-500">

            <x-h.h4>計算機概論</x-h.h4>


            <div class="flex items-center justify-between mb-8">
                <x-h.h6>年級 : <span class="font-semibold text-gray-900 text-md md:text-lg">大一【上】</span></x-h.h6>

                <x-h.h6>課程 : <span class="font-semibold text-gray-900 text-md md:text-lg">必修</span></x-h.h6>
        </x-div.card-box>

        <a href="{{ route('products.index', ['tags' => ['introduction-to-computer-science']]) }}"
            class="px-6 py-4 bg-info font-semibold text-white text-lg rounded-xl hover:bg-blue-700 transition ease-in-out duration-500">前往了解</a>
</div>
</div>
</section>
</div>
