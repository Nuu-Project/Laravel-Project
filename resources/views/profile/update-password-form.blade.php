<section>
    <div class="max-w-md mx-auto p-6 bg-white shadow-md rounded-lg">
        <header class="text-center mb-6">
            <h2 class="text-lg font-medium text-gray-900">
                更新密碼
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                確保您的帳戶使用長且隨機的密碼以保持安全。
            </p>
        </header>

        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('put')

            <div>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <x-text-input id="update_password_current_password" name="current_password" type="password"
                    autocomplete="current-password" placeholder="目前密碼" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-600 text-sm" />
            </div>

            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" />
                <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password"
                    placeholder="新密碼" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-600 text-sm" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    autocomplete="new-password" placeholder="確認密碼" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
            </div>

            <div>
                <x-primary-button>
                    儲存
                </x-primary-button>
            </div>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-center text-sm text-gray-600 mt-2">
                    已儲存。
                </p>
            @endif
        </form>
    </div>
</section>
