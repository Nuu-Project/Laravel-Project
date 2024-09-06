<x-mail::message>
#歡迎

請點擊驗證信以正常登入

<x-mail::button :url="$verificationUrl">
點擊驗證
</x-mail::button>

謝謝<br>
{{ config('app.name') }}
</x-mail::message>
