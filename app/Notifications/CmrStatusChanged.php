<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Str;
use App\Models\Cmr;

class CmrStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Cmr $cmr, public string $actorRole, public string $action, public ?string $note, public $actorName)
    {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $noReg = $this->cmr->no_reg ?? $this->cmr->id;
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

        $title = "CMR {$noReg} {$actionLabel}";
        $message = "CMR {$noReg} was {$actionLabel} by {$actorLabel}.";
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
            $routeNameIndex = $roleKey . '.cmr.index';
            if (\Illuminate\Support\Facades\Route::has($routeNameIndex)) {
                $url = route($routeNameIndex);
            }
        } catch (\Throwable $e) {
        }

        return [
            'title' => $title,
            'message' => $message,
            'cmr_no_reg' => $this->cmr->no_reg ?? null,
            'cmr_id' => $this->cmr->id,
            'actor_role' => $this->actorRole,
            'actor_name' => $this->actorName,
            'action' => $this->action,
            'note' => $this->note,
            'url' => $url,
        ];
    }
}
