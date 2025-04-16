<!DOCTYPE html>
<html>
<head>
    <title>留言已刪除通知</title>
</head>
<body>
    <p>親愛的 {{ $name ?? '使用者' }} 您好，</p>

    <p>我們很抱歉地通知您，您在我們平台上的留言已被管理員刪除。</p>

    @if ($message)
        <p><strong>被刪除的留言內容：</strong></p>
        <p>{{ $message }}</p>
    @endif

    <p>如果您對此有任何疑問，請聯繫我們的客服團隊。</p>

    <p>感謝您的理解與合作。</p>

    <p><strong>{{ config('app.name') }}</strong></p>
</body>
</html>
