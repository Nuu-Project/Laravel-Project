<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST"
            action="{{ route('user.products.messages.update', ['product' => $productId, 'chirp' => $chirp]) }}">
            @csrf
            @method('patch')
            <textarea name="message"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('message', $chirp->message) }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <div class="mt-4 space-x-2">
                <x-primary-button>{{ __('儲存') }}</x-primary-button>
                <a href="{{ route('products.show', ['product' => $productId]) }}">
                    <x-primary-button type="button">{{ __('取消') }}</x-primary-button>
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
