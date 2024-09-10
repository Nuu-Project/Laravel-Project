<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('感謝您的註冊！在開始之前，請通過點擊我們剛剛發送給您的電子郵件中的鏈接來驗證您的電子郵件地址。如果您沒有收到電子郵件，我們將很樂意再發送一封。') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('新的驗證連結已傳送至郵箱') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('email/verification-notification') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('重新寄一封驗證信') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
