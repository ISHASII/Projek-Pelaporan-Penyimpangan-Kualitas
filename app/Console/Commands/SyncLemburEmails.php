<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SyncLemburEmails extends Command
{
    protected $signature = 'sync:lembur-emails {--apply} {--limit=0} {--overwrite}';
    protected $description = 'Sync user emails from lembur.ct_users_hash.user_email to local users table.';

    public function handle()
    {
        $apply = $this->option('apply');
        $limit = intval($this->option('limit'));

        $query = User::query()->whereNull('email');
        if ($limit > 0) $query->limit($limit);
        $this->info('Fetching users without email...');
        $users = $query->get();
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();
        foreach ($users as $u) {
            // lookup in lembur
            try {
                $email = DB::connection('lembur')->table('ct_users_hash')->where('npk', $u->npk)->value('user_email');
                if ($email) {
                    $this->line("\nFound: {$u->npk} -> {$email}");
                    // If email already belongs to another local user, skip unless overwrite is specified
                    $exists = User::where('email', $email)->where('id', '!=', $u->id)->exists();
                    if ($exists && ! $this->option('overwrite')) {
                        $this->line(" Skipped (email in use)");
                    } else {
                        if ($apply) {
                            $u->email = $email;
                            $u->save();
                            $this->info(" Updated: {$u->id}");
                        }
                    }
                }
            } catch (\Throwable $e) {
                $this->error('Failed: ' . $e->getMessage());
            }
            $bar->advance();
        }
        $bar->finish();
        $this->line("\nDone.");
    }
}
