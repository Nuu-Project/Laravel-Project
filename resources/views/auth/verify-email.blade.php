<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('成功傳送驗證信件。') }}
    </div>

    <div class="flex items-center justify-end mt-4">
        <a class="no-underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('dashboard') }}">
            {{ __('點擊驗證信跳轉回首頁') }}
        </a>
    </div>
</x-guest-layout>
