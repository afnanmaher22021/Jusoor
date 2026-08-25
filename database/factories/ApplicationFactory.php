<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'accepted', 'rejected']);

        return [
            'user_id' => User::factory()->state(['role' => 'volunteer']),
            'opportunity_id' => Opportunity::factory(),
            'status' => $status,
            'message' => fake()->optional(0.5)->sentence(8),
            'responded_at' => $status === 'pending' ? null : now()->subDays(fake()->numberBetween(1, 10)),
            'reviewer_note' => $status === 'pending' ? null : fake()->optional(0.7)->sentence(4),
        ];
    }
}
