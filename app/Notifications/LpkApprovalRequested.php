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
        // Only use mail channel if the notifiable has an email available either locally or from lembur (via routeNotificationForMail)
        $channels = ['database'];
        $email = null;
        if (method_exists($notifiable, 'routeNotificationForMail')) {
            try { $email = $notifiable->routeNotificationForMail($this); } catch (\Throwable $e) { $email = null; }
        }
        if (empty($email) && !empty($notifiable->email)) {
            $email = $notifiable->email;
        }
        if (!empty($email)) {
            $channels[] = 'mail';
        }
        return $channels;
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
        $email = $notifiable->email ?? null;
        if (empty($email) && isset($notifiable->npk)) {
            try {
                $email = \DB::connection('lembur')->table('ct_users_hash')->where('npk', $notifiable->npk)->value('user_email');
            } catch (\Throwable $e) {
                // ignore
            }
        }
        return [
            'lpk_id' => $this->lpk->id,
            'no_reg' => $this->lpk->no_reg,
            'message' => 'Permintaan persetujuan LPK ' . $this->lpk->no_reg,
            // Include recipient email for convenience / logging
            'user_email' => $email,
        ];
    }
}
