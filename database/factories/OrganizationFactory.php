<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'organization']),
            'name' => fake()->company() . ' ' . fake()->randomElement(['الخيرية', 'التنموية', 'الإنسانية']),
            'description' => fake()->paragraph(2),
            'website' => fake()->url(),
            'city' => fake()->randomElement(['غزة', 'رام الله', 'نابلس', 'الخليل', 'جنين', 'بيت لحم']),
            'founded_year' => (string) fake()->numberBetween(2000, 2024),
            'verified' => fake()->boolean(70),
        ];
    }
}
