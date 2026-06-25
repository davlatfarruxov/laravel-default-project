<?php

namespace Database\Factories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Campaign>
 */
class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        $budget = $this->faker->numberBetween(2, 80) * 1_000_000;
        $status = $this->faker->randomElement(['draft', 'active', 'active', 'paused', 'completed']);
        $spent  = in_array($status, ['active', 'paused', 'completed'])
            ? $this->faker->numberBetween((int) ($budget * 0.1), $budget)
            : 0;

        $start = $this->faker->dateTimeBetween('-5 months', '+1 week');
        $end   = (clone $start)->modify('+' . $this->faker->numberBetween(14, 90) . ' days');

        $themes = ['Yozgi', 'Qishki', 'Bahor', 'Black Friday', 'Yangi yil', 'Ramazon', 'Maktab', 'Brend', 'Re-targeting', 'Launch'];
        $kinds  = ['chegirma', 'aksiya', 'kampaniya', 'promo', 'tanlov', 'webinar'];

        return [
            'name'        => $this->faker->randomElement($themes) . ' ' . $this->faker->randomElement($kinds),
            'channel'     => $this->faker->randomElement(['email', 'social', 'seo', 'ppc', 'sms', 'event']),
            'status'      => $status,
            'budget'      => $budget,
            'spent'       => $spent,
            'start_date'  => $start,
            'end_date'    => $end,
            'description' => $this->faker->sentence(10),
        ];
    }
}
