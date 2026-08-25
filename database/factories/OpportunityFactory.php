<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Opportunity;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Opportunity>
 */
class OpportunityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'category_id' => Category::factory(),
            'title' => fake()->randomElement([
                'مبادرة تنظيف الشاطئ',
                'تدريس الرياضيات للأطفال',
                'توزيع طرود غذائية',
                'حملة التبرع بالدم',
                'تنسيق حديقة المجتمع',
                'ورشة تدريب على البرمجة',
                'مساعدة كبار السن',
                'تنظيم فعالية يوم التطوع',
                'رعاية أطفال التوحد',
                'زراعة الأشجار في المدينة',
            ]),
            'description' => fake()->paragraph(4),
            'location' => fake()->randomElement(['غزة', 'رام الله', 'نابلس', 'الخليل', 'جنين', 'بيت لحم']),
            'required_hours' => fake()->numberBetween(2, 60),
            'max_volunteers' => fake()->numberBetween(3, 30),
            'status' => 'open',
            'skills_required' => fake()->optional(0.6)->sentence(),
            'starts_at' => now()->addDays(fake()->numberBetween(2, 30)),
            'ends_at' => now()->addDays(fake()->numberBetween(31, 90)),
        ];
    }
}
