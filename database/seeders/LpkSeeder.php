<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lpk;
use App\Models\User;

class LpkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ensure there are some users to attach as approvers if needed
        $users = User::take(10)->get();

        Lpk::factory()->count(15)->make()->each(function ($lpk) use ($users) {
            // optionally assign approver ids from existing users if status is approved
            if (strtolower($lpk->secthead_status ?? '') === 'approved' && $users->count()) {
                $lpk->secthead_approver_id = $users->random()->id;
            }
            if (strtolower($lpk->depthead_status ?? '') === 'approved' && $users->count()) {
                $lpk->depthead_approver_id = $users->random()->id;
            }
            if (strtolower($lpk->ppchead_status ?? '') === 'approved' && $users->count()) {
                $lpk->ppchead_approver_id = $users->random()->id;
            }

            // save model (factory made an instance not persisted)
            $lpk->save();
        });
    }
}