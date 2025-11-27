<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\Lpk;

class LpkStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Lpk $lpk, public string $actorRole, public string $action, public ?string $note, public $actorName)
    {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $noReg = $this->lpk->no_reg ?? $this->lpk->id;
        $actionLabel = strtolower($this->action) === 'approved' ? 'disetujui' : 'ditolak';

        // Map actor role to Indonesian label for friendlier messages
        $actorRoleLower = strtolower($this->actorRole);
        if (Str::contains($actorRoleLower, 'sect')) {
            $actorLabel = 'Kepala Seksi';
        } elseif (Str::contains($actorRoleLower, 'dept')) {
            $actorLabel = 'Kepala Departemen';
        } elseif (Str::contains($actorRoleLower, 'ppc')) {
            $actorLabel = 'Kepala PPC';
        } elseif (Str::contains($actorRoleLower, 'qc') || Str::contains($actorRoleLower, 'quality')) {
            $actorLabel = 'Quality Control';
        } else {
            $actorLabel = Str::title($this->actorRole);
        }

        // Build a friendly title and message in Indonesian
        $title = "LPK {$noReg} {$actionLabel}";
        $message = "LPK {$noReg} telah {$actionLabel} oleh {$actorLabel}.";
        if ($this->note) {
            $message .= ' Keterangan: ' . Str::limit($this->note, 200);
        }

        // Determine a role-specific show URL for the recipient
        $recipientRole = strtolower(preg_replace('/[\s_\-]/', '', $notifiable->role ?? 'qc'));
        $roleMap = [
            'qc' => 'qc',
            'quality' => 'qc',
            'sect' => 'secthead',
            'dept' => 'depthead',
            'ppc' => 'ppchead',
        ];

        $roleKey = 'qc';
        foreach ($roleMap as $key => $val) {
            if (Str::contains($recipientRole, $key)) {
                $roleKey = $val;
                break;
            }
        }

        // Prefer role-specific index route (show pages were removed). If it's not available, fallback to '#'.
        $url = '#';
        try {
            $routeNameIndex = $roleKey . '.lpk.index';
            if (Route::has($routeNameIndex)) {
                $url = route($routeNameIndex);
            }
        } catch (\Throwable $e) {
            // ignore and keep '#'
        }

        return [
            'title' => $title,
            'message' => $message,
            'lpk_no_reg' => $this->lpk->no_reg ?? null,
            'lpk_id' => $this->lpk->id,
            'actor_role' => $this->actorRole,
            'actor_name' => $this->actorName,
            'action' => $this->action, // 'approved' or 'rejected'
            'note' => $this->note,
            'url' => $url,
        ];
    }
}
