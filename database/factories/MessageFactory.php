<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'subject' => fake()->sentence(3),
            'body' => fake()->paragraph(2),
            'read_at' => fake()->optional(0.5)->dateTime(),
        ];
    }
}
