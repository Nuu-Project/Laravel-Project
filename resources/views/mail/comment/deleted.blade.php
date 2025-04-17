<x-mail::message>
{{"留言刪除通知"}}

請注意：此信件由系統自動發送，請勿直接回覆此信

親愛的 {{ $name ?? '使用者' }}：

我們很抱歉地通知您，您在我們的書網上的留言已被管理員刪除。

{{-- @if ($user && $user->suspend_reason)
    原因：{{ $user->suspend_reason }}<br>
@endif --}}

@if ($message)
<x-mail::panel>
{{-- 使用 panel 元件來突顯被刪除的內容 --}}
<strong>被刪除的留言內容：</strong><br>
{{ $message }}
</x-mail::panel>
@endif

<x-mail::button :url="config('app.url')">
返回書網
</x-mail::button>

感謝您的理解與合作。<br>
{{ config('app.name') }} 團隊

<small style="color: #718096;">
此信件由系統自動發送，請勿直接回覆
</small>
</x-mail::message>
