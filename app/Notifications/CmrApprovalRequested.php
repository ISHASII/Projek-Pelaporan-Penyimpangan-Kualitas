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

    public function __construct(Cmr $cmr)
    {
        $this->cmr = $cmr;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $url = route('qc.cmr.index');
        return (new MailMessage)
                    ->subject('CMR Approval Request: ' . $this->cmr->no_reg)
                    ->greeting('Hello ' . ($notifiable->name ?? ''))
                    ->line('QC has requested approval for CMR with Reg No: ' . $this->cmr->no_reg)
                    ->action('View CMR', $url)
                    ->line('Please open the application to review and take action.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'cmr_id' => $this->cmr->id,
            'no_reg' => $this->cmr->no_reg,
            'message' => 'CMR approval requested: ' . $this->cmr->no_reg,
        ];
    }
}
