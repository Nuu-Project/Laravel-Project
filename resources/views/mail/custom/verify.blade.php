<x-mail::message>
<div class="welcome-name">{{ $userName }}</div>您好
歡迎註冊
請點擊驗證登入

<x-mail::button :url="$verificationUrl">
驗證
</x-mail::button>

謝謝您!<br>
#如果您不認識本網站請不要點擊驗證
</x-mail::message>