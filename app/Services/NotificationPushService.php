<?php

namespace App\Services;

use App\Models\NotificationPush;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationPushService
{
    /**
     *
     *
     * @param string
     * @param string|null
     * @param string
     * @return NotificationPush|null
     */
    public static function store(string $npk, ?string $email, string $message): ?NotificationPush
    {
        try {

            $phoneNumber = self::getPhoneNumber($npk);

            $userEmail = self::getUserEmail($npk) ?? $email;

            if (empty($phoneNumber) && empty($userEmail)) {
                Log::warning('NotificationPush: No phone or email found for NPK', ['npk' => $npk]);
                return null;
            }

            return NotificationPush::create([
                'phone_number' => $phoneNumber ?? '',
                'user_email' => $userEmail,
                'message' => $message,
                'flag' => 'queue',
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('NotificationPush: Failed to store notification', [
                'npk' => $npk,
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     *
     *
     * @param array
     * @param string
     * @return int
     */
    public static function storeMany(array $recipients, string $message): int
    {
        $count = 0;
        foreach ($recipients as $recipient) {
            $npk = $recipient['npk'] ?? $recipient->npk ?? '';
            $email = $recipient['email'] ?? $recipient->email ?? null;

            if (!empty($npk) || !empty($email)) {
                $result = self::store($npk, $email, $message);
                if ($result) {
                    $count++;
                }
            }
        }
        return $count;
    }

    /**
     *
     *
     * @param string $npk
     * @return string|null
     */
    protected static function getPhoneNumber(string $npk): ?string
    {
        try {
            $record = DB::connection('isd')
                ->table('hp')
                ->where('npk', $npk)
                ->select('no_hp')
                ->first();

            return $record?->no_hp;
        } catch (\Throwable $e) {
            Log::warning('NotificationPush: Failed to get phone number from isd', [
                'npk' => $npk,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     *
     *
     * @param string
     * @return string|null
     */
    protected static function getUserEmail(string $npk): ?string
    {
        try {
            $record = DB::connection('lembur')
                ->table('ct_users_hash')
                ->where('npk', $npk)
                ->select('user_email')
                ->first();

            return $record?->user_email;
        } catch (\Throwable $e) {
            Log::warning('NotificationPush: Failed to get user email from lembur', [
                'npk' => $npk,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     *
     *
     * @param object
     * @param string
     * @param string
     * @return string
     */
    public static function formatLpkMessage(object $lpk, string $action, string $fromRole = ''): string
    {
        $noReg = $lpk->no_reg ?? $lpk->id;

        switch ($action) {
            case 'request_approval':
                return "Permintaan Persetujuan LPK: {$noReg}. LPK baru memerlukan persetujuan Anda. Silakan login untuk meninjau.";
            case 'approved':
                return "LPK {$noReg} telah disetujui oleh {$fromRole}. Silakan lanjutkan proses berikutnya.";
            case 'rejected':
                return "LPK {$noReg} telah ditolak oleh {$fromRole}. Silakan periksa catatan penolakan.";
            default:
                return "Notifikasi LPK: {$noReg}";
        }
    }

    /**
     *
     *
     * @param object
     * @param string
     * @param string
     * @return string
     */
    public static function formatNqrMessage(object $nqr, string $action, string $fromRole = ''): string
    {
        $noReg = $nqr->no_reg_nqr ?? $nqr->id;

        switch ($action) {
            case 'request_approval':
                return "Permintaan Persetujuan NQR: {$noReg}. NQR baru memerlukan persetujuan Anda. Silakan login untuk meninjau.";
            case 'approved':
                return "NQR {$noReg} telah disetujui oleh {$fromRole}. Silakan lanjutkan proses berikutnya.";
            case 'rejected':
                return "NQR {$noReg} telah ditolak oleh {$fromRole}. Silakan periksa catatan penolakan.";
            default:
                return "Notifikasi NQR: {$noReg}";
        }
    }

    /**
     *
     *
     * @param object
     * @param string
     * @param string
     * @return string
     */
    public static function formatCmrMessage(object $cmr, string $action, string $fromRole = ''): string
    {
        $noReg = $cmr->no_reg ?? $cmr->id;

        switch ($action) {
            case 'request_approval':
                return "Permintaan Persetujuan CMR: {$noReg}. CMR baru memerlukan persetujuan Anda. Silakan login untuk meninjau.";
            case 'approved':
                return "CMR {$noReg} telah disetujui oleh {$fromRole}. Silakan lanjutkan proses berikutnya.";
            case 'rejected':
                return "CMR {$noReg} telah ditolak oleh {$fromRole}. Silakan periksa catatan penolakan.";
            default:
                return "Notifikasi CMR: {$noReg}";
        }
    }
}
