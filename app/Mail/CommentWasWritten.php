<?php

namespace App\Mail;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class CommentWasWritten extends Mailable
{
    use Queueable, SerializesModels;

    private Issue $issue;
    private User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Issue $issue, User $user)
    {
        $this->issue = $issue;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(Config::get('mail.from.address'), Config::get('mail.from.name'))
            ->subject('Добавлен новый комментарий')
            ->markdown('emails.issue-change-status-markdown.blade', [
                'issue' => $this->issue,
                'user' => $this->user,
            ]);
    }
}
