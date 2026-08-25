<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpportunityManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_can_create_opportunity(): void
    {
        $orgUser = User::factory()->create(['role' => 'organization']);
        $category = Category::create(['name' => 'تعليم', 'slug' => 'edu']);

        $response = $this->actingAs($orgUser)->post('/organization/opportunities', [
            'title' => 'تدريس الرياضيات للأطفال',
            'category_id' => $category->id,
            'description' => 'مطلوب متطوع لتدريس الرياضيات لطلاب المرحلة الابتدائية.',
            'location' => 'غزة',
            'required_hours' => 10,
            'max_volunteers' => 5,
            'starts_at' => now()->addDays(2)->format('Y-m-d'),
            'ends_at' => now()->addDays(30)->format('Y-m-d'),
            'skills_required' => 'معرفة بالرياضيات والصبر',
        ]);

        $response->assertRedirect('/organization/opportunities');
        $this->assertDatabaseHas('opportunities', [
            'title' => 'تدريس الرياضيات للأطفال',
            'location' => 'غزة',
        ]);
    }

    public function test_organization_can_update_opportunity(): void
    {
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create([
            'user_id' => $orgUser->id,
            'name' => 'مؤسسة الاختبار',
            'city' => 'غزة',
        ]);
        $category = Category::create(['name' => 'تعليم', 'slug' => 'edu']);
        $opportunity = Opportunity::factory()->create([
            'organization_id' => $org->id,
            'category_id' => $category->id,
            'title' => 'العنوان القديم',
        ]);

        $response = $this->actingAs($orgUser)->put("/organization/opportunities/{$opportunity->id}", [
            'title' => 'العنوان المعدل',
            'category_id' => $category->id,
            'description' => 'وصف معدل',
            'location' => 'رام الله',
            'required_hours' => 15,
            'max_volunteers' => 10,
            'starts_at' => now()->addDays(1)->format('Y-m-d'),
            'ends_at' => now()->addDays(20)->format('Y-m-d'),
            'status' => 'open',
        ]);

        $response->assertRedirect('/organization/opportunities');
        $this->assertDatabaseHas('opportunities', [
            'id' => $opportunity->id,
            'title' => 'العنوان المعدل',
            'location' => 'رام الله',
        ]);
    }

    public function test_organization_can_delete_opportunity(): void
    {
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create([
            'user_id' => $orgUser->id,
            'name' => 'مؤسسة الاختبار',
            'city' => 'غزة',
        ]);
        $opportunity = Opportunity::factory()->create(['organization_id' => $org->id]);

        $response = $this->actingAs($orgUser)->delete("/organization/opportunities/{$opportunity->id}");

        $response->assertRedirect('/organization/opportunities');
        $this->assertDatabaseMissing('opportunities', ['id' => $opportunity->id]);
    }

    public function test_volunteer_cannot_create_opportunity(): void
    {
        $volunteer = User::factory()->create(['role' => 'volunteer']);
        $category = Category::create(['name' => 'تعليم', 'slug' => 'edu']);

        $response = $this->actingAs($volunteer)->post('/organization/opportunities', [
            'title' => 'فرصة غير مصرح بها',
            'category_id' => $category->id,
            'description' => 'وصف',
            'location' => 'غزة',
            'required_hours' => 5,
            'max_volunteers' => 2,
            'starts_at' => now()->format('Y-m-d'),
            'ends_at' => now()->addDays(5)->format('Y-m-d'),
        ]);

        $response->assertStatus(403);
    }
}
