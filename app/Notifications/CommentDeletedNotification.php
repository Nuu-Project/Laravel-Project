<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentDeletedNotification extends Notification
{
    use Queueable;

    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('您的留言已被刪除通知')
            ->greeting("你好 {$notifiable->name}，")
            ->line('您的留言已被刪除。')
            ->line("留言内容：{$this->message->message}")
            ->line('如果您有任何疑問，請聯繫管理員。')
            ->line('感謝您的支持');
    }
}
