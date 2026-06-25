<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        if (Lead::count() > 0) {
            $this->command?->warn('• Leads mavjud — seed o\'tkazib yuborildi.');
            return;
        }

        $campaignIds = Campaign::pluck('id')->all();
        // Mas'ul xodimlar: manager + oddiy foydalanuvchilar
        $userIds = User::role(['manager', 'user'])->pluck('id')->all();

        Lead::factory()->count(60)->make()->each(function (Lead $lead) use ($campaignIds, $userIds) {
            $lead->campaign_id = ! empty($campaignIds) && rand(0, 100) < 80 ? $campaignIds[array_rand($campaignIds)] : null;
            $lead->assigned_to = ! empty($userIds) && rand(0, 100) < 70 ? $userIds[array_rand($userIds)] : null;
            $lead->save();
        });

        $this->command?->info('✓ 60 ta mijoz yaratildi.');
    }
}
