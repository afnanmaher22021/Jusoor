<?php

namespace Database\Factories;

use App\Models\Opportunity;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Participation>
 */
class ParticipationFactory extends Factory
{
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-5 months', 'now');

        return [
            'user_id' => User::factory()->state(['role' => 'volunteer']),
            'opportunity_id' => Opportunity::factory(),
            'hours' => fake()->randomFloat(1, 1, 8),
            'work_date' => $date,
            'notes' => fake()->optional(0.4)->sentence(5),
            'status' => 'approved',
            'approved_by' => User::factory()->state(['role' => 'organization']),
            'approved_at' => $date,
        ];
    }
}
