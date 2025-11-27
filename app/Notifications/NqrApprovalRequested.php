<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Nqr;

class NqrApprovalRequested extends Notification
{
    use Queueable;

    protected $nqr;

    public function __construct(Nqr $nqr)
    {
        $this->nqr = $nqr;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $url = route('qc.nqr.index');
        return (new MailMessage)
                    ->subject('NQR Approval Request: ' . ($this->nqr->no_reg_nqr ?? $this->nqr->id))
                    ->greeting('Hello ' . ($notifiable->name ?? ''))
                    ->line('QC has requested approval for NQR with Reg No: ' . ($this->nqr->no_reg_nqr ?? ''))
                    ->action('View NQR', $url)
                    ->line('Please open the application to review and take action.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'nqr_id' => $this->nqr->id,
            'no_reg' => $this->nqr->no_reg_nqr ?? null,
            'message' => 'NQR approval requested: ' . ($this->nqr->no_reg_nqr ?? ''),
        ];
    }
}
