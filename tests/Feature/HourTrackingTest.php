<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HourTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_can_log_hours_for_accepted_volunteer(): void
    {
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create(['user_id' => $orgUser->id, 'name' => 'جمعية الأمل', 'city' => 'غزة']);
        $opportunity = Opportunity::factory()->create(['organization_id' => $org->id]);

        $volunteer = User::factory()->create(['role' => 'volunteer']);
        Application::create([
            'opportunity_id' => $opportunity->id,
            'user_id' => $volunteer->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($orgUser)->post("/organization/hours/{$opportunity->id}", [
            'user_id' => $volunteer->id,
            'hours' => 4.5,
            'work_date' => now()->format('Y-m-d'),
            'notes' => 'أداء متميز في اليوم التطوعي',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('participation', [
            'opportunity_id' => $opportunity->id,
            'user_id' => $volunteer->id,
            'hours' => 4.5,
            'status' => 'approved',
        ]);

        // Volunteer receives notification
        $this->assertDatabaseHas('notifications', [
            'user_id' => $volunteer->id,
            'type' => 'hours',
        ]);

        // Volunteer total approved hours check
        $this->assertEquals(4.5, $volunteer->totalApprovedHours());
    }

    public function test_organization_cannot_log_hours_for_unaccepted_volunteer(): void
    {
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create(['user_id' => $orgUser->id, 'name' => 'جمعية الأمل', 'city' => 'غزة']);
        $opportunity = Opportunity::factory()->create(['organization_id' => $org->id]);

        $volunteer = User::factory()->create(['role' => 'volunteer']);
        // Application is pending, not accepted
        Application::create([
            'opportunity_id' => $opportunity->id,
            'user_id' => $volunteer->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($orgUser)->post("/organization/hours/{$opportunity->id}", [
            'user_id' => $volunteer->id,
            'hours' => 3,
            'work_date' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('participation', [
            'user_id' => $volunteer->id,
        ]);
    }

    public function test_volunteer_can_view_hours_history(): void
    {
        $volunteer = User::factory()->create(['role' => 'volunteer']);
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create(['user_id' => $orgUser->id, 'name' => 'جمعية الأمل', 'city' => 'غزة']);
        $opportunity = Opportunity::factory()->create(['organization_id' => $org->id, 'title' => 'حملة تشجير']);

        Participation::create([
            'user_id' => $volunteer->id,
            'opportunity_id' => $opportunity->id,
            'hours' => 6,
            'work_date' => now()->format('Y-m-d'),
            'status' => 'approved',
        ]);

        $response = $this->actingAs($volunteer)->get('/volunteer/hours');

        $response->assertStatus(200);
        $response->assertSee('حملة تشجير');
        $response->assertSee('6');
    }
}
