@php
    use App\Enums\ProductStatus;
@endphp

<x-template-user-layout>

    <x-flex-container>
        <x-div.container>
            @if (session('success'))
                <x-div.green role="alert">
                    <x-span.inline>{{ session('success') }}</x-span.inline>
                </x-div.green>
            @endif

            @if (session('error'))
                <x-div.red role="alert">
                    <x-span.inline>{{ session('error') }}</x-span.inline>
                </x-div.red>
            @endif

            <div>
                <x-h.h3>我的商品</x-h.h3>
                <form action="{{ route('user.products.index') }}" method="GET">
                    <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 items-stretch md:items-center">
                        <x-input.search type="text" name="filter[name]" placeholder="搜尋商品名稱..."
                            value="{{ request('filter.name') }}">
                        </x-input.search>
                        <x-button.search>
                            搜尋
                        </x-button.search>
                    </div>
                </form>
            </div>

            <div class="flex flex-col w-full min-h-screen">
                <main class="flex min-h-[calc(100vh_-_theme(spacing.16))] flex-1 flex-col gap-4 p-4 md:gap-8 md:p-10">
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @forelse ($userProducts as $product)
                            <x-product.user-card :product="$product" />
                        @empty
                            <x-product.empty-state />
                        @endforelse
                    </div>

                    <div>
                        {{ $userProducts->links() }}
                    </div>
                </main>
            </div>
        </x-div.container>
    </x-flex-container>
</x-template-user-layout>
