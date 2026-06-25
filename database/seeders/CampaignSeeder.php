<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        if (Campaign::count() > 0) {
            $this->command?->warn('• Campaigns mavjud — seed o\'tkazib yuborildi.');
            return;
        }

        Campaign::factory()->count(12)->create();

        $this->command?->info('✓ 12 ta kampaniya yaratildi.');
    }
}
