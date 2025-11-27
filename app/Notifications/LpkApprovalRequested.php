<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Lpk;

class LpkApprovalRequested extends Notification
{
    use Queueable;

    protected $lpk;

    public function __construct(Lpk $lpk)
    {
        $this->lpk = $lpk;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        // Show pages removed; link to index for the recipient role instead
        $url = route('qc.lpk.index');
        return (new MailMessage)
                    ->subject('Permintaan Persetujuan LPK: ' . $this->lpk->no_reg)
                    ->greeting('Halo ' . ($notifiable->name ?? ''))
                    ->line('QC telah meminta persetujuan untuk LPK dengan No. Reg: ' . $this->lpk->no_reg)
                    ->action('Lihat LPK', $url)
                    ->line('Silakan buka aplikasi untuk meninjau dan memberi keputusan.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'lpk_id' => $this->lpk->id,
            'no_reg' => $this->lpk->no_reg,
            'message' => 'Permintaan persetujuan LPK ' . $this->lpk->no_reg,
        ];
    }
}
