<x-mail::message>
{{"留言刪除通知"}}

請注意：此信件由系統自動發送，請勿直接回覆此信

親愛的 {{ $name ?? '使用者' }}：

我們很抱歉地通知您違反了我們的使用條款，<br>因此您在書網上的留言已被管理員刪除。

@if ($message)
<x-mail::panel>
<strong>被刪除的留言內容：</strong><br>
{{ $message }}
</x-mail::panel>
@endif

<x-mail::button :url="config('app.url')">
返回書網
</x-mail::button>

感謝您的理解與合作<br>
聯大二手書網

<small style="color: #718096;">
此信件由系統自動發送，請勿直接回覆
</small>
</x-mail::message>
