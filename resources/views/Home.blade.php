<x-template-layout>

    <section>
        <x-div.container-screen mode="default">
            <p class="font-black text-gray-500 text-md md:text-xl text-center uppercase mb-6">平台理念</p>
            <x-h.h1>讓社恐和I人<br>都能有個安靜的空間買書</x-h.h1>
            <x-icon></x-icon>
        </x-div.container-screen>
    </section>
    <x-product.card :products="$products" />
    <x-page.last></x-page.last>

    <script>
        feather.replace()
    </script>
</x-template-layout>
