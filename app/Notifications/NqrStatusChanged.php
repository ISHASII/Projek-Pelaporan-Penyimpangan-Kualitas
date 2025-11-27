<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Str;
use App\Models\Nqr;

class NqrStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Nqr $nqr, public string $actorRole, public string $action, public ?string $note, public $actorName)
    {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $noReg = $this->nqr->no_reg_nqr ?? $this->nqr->id;
        $actionLabel = strtolower($this->action) === 'approved' ? 'approved' : 'rejected';

        $actorRoleLower = strtolower($this->actorRole);
        if (Str::contains($actorRoleLower, 'sect')) {
            $actorLabel = 'Sect Head';
        } elseif (Str::contains($actorRoleLower, 'dept')) {
            $actorLabel = 'Dept Head';
        } elseif (Str::contains($actorRoleLower, 'ppc')) {
            $actorLabel = 'PPC Head';
        } elseif (Str::contains($actorRoleLower, 'qc') || Str::contains($actorRoleLower, 'quality')) {
            $actorLabel = 'Quality Control';
        } else {
            $actorLabel = Str::title($this->actorRole);
        }

        $title = "NQR {$noReg} {$actionLabel}";
        $message = "NQR {$noReg} was {$actionLabel} by {$actorLabel}.";
        if ($this->note) {
            $message .= ' Note: ' . Str::limit($this->note, 200);
        }

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

        $url = '#';
        try {
            $routeNameIndex = $roleKey . '.nqr.index';
            if (\Illuminate\Support\Facades\Route::has($routeNameIndex)) {
                $url = route($routeNameIndex);
            }
        } catch (\Throwable $e) {
        }

        return [
            'title' => $title,
            'message' => $message,
            'nqr_no_reg' => $this->nqr->no_reg_nqr ?? null,
            'nqr_id' => $this->nqr->id,
            'actor_role' => $this->actorRole,
            'actor_name' => $this->actorName,
            'action' => $this->action,
            'note' => $this->note,
            'url' => $url,
        ];
    }
}
