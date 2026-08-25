<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['application', 'application_response', 'hours', 'info']),
            'title' => fake()->sentence(3),
            'body' => fake()->sentence(8),
            'action_url' => fake()->optional(0.6)->url(),
            'read_at' => fake()->optional(0.6)->dateTime(),
        ];
    }
}
