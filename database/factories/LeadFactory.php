<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['new', 'new', 'contacted', 'contacted', 'qualified', 'won', 'lost']);

        return [
            'name'        => $this->faker->name(),
            'email'       => $this->faker->unique()->safeEmail(),
            'phone'       => '+998 ' . $this->faker->numerify('## ### ## ##'),
            'company'     => $this->faker->company(),
            'source'      => $this->faker->randomElement(['website', 'social', 'referral', 'ads', 'event', 'other']),
            'status'      => $status,
            'value'       => $this->faker->numberBetween(1, 50) * 1_000_000,
            'campaign_id' => null,
            'assigned_to' => null,
            'notes'       => $this->faker->optional(0.5)->sentence(8),
            'created_at'  => $this->faker->dateTimeBetween('-5 months', 'now'),
        ];
    }
}
