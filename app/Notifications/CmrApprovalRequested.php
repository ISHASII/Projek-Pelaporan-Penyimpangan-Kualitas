<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Cmr;

class CmrApprovalRequested extends Notification
{
    use Queueable;

    protected $cmr;
    protected $targetRole;

    public function __construct(Cmr $cmr, ?string $targetRole = null)
    {
        $this->cmr = $cmr;
        $this->targetRole = $targetRole;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $recipientName = $notifiable->name ?? 'Bapak/Ibu';

        return (new MailMessage)
            ->subject('Permintaan Persetujuan CMR: ' . $this->cmr->no_reg)
            ->view('emails.cmr_approval_requested', [
                'cmr' => $this->cmr,
                'recipientName' => $recipientName,
                'targetRole' => $this->targetRole,
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'cmr_id' => $this->cmr->id,
            'no_reg' => $this->cmr->no_reg,
            'message' => 'CMR approval requested: ' . $this->cmr->no_reg,
            'target_role' => $this->targetRole,
        ];
    }
}
