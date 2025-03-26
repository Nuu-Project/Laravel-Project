<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommentDeletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $commentContent; // 被刪除的留言內容

    public $userName;       // 創建留言的使用者名稱

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(?string $commentContent = null, ?string $userName = null)
    {
        $this->commentContent = $commentContent;
        $this->userName = $userName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '您的留言已被刪除通知',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.comment.deleted',
            with: [
                'commentContent' => $this->commentContent,
                'userName' => $this->userName,
            ],
        );
    }
}
