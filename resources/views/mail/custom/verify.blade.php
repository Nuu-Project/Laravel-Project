<x-mail::message>
<div class="welcome-name">{{ Auth::user()->name }}</div>您好
歡迎註冊
請點擊驗證信以正常登入

<x-mail::button :url="$verificationUrl">
點擊驗證
</x-mail::button>

謝謝您!<br>
</x-mail::message>
