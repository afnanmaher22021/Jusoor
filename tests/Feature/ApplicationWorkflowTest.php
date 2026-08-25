<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_volunteer_can_apply_to_opportunity(): void
    {
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create(['user_id' => $orgUser->id, 'name' => 'جمعية الأمل', 'city' => 'غزة']);
        $opportunity = Opportunity::factory()->create(['organization_id' => $org->id, 'status' => 'open', 'max_volunteers' => 5]);

        $volunteer = User::factory()->create(['role' => 'volunteer', 'name' => 'أحمد']);

        $response = $this->actingAs($volunteer)->post("/opportunities/{$opportunity->id}/apply", [
            'message' => 'أرغب في المشاركة والمساعدة.',
        ]);

        $response->assertRedirect("/opportunities/{$opportunity->id}");
        $this->assertDatabaseHas('applications', [
            'opportunity_id' => $opportunity->id,
            'user_id' => $volunteer->id,
            'status' => 'pending',
            'message' => 'أرغب في المشاركة والمساعدة.',
        ]);

        // Notification received by organization user
        $this->assertDatabaseHas('notifications', [
            'user_id' => $orgUser->id,
            'type' => 'application',
        ]);
    }

    public function test_volunteer_cannot_apply_twice_to_same_opportunity(): void
    {
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create(['user_id' => $orgUser->id, 'name' => 'جمعية الأمل', 'city' => 'غزة']);
        $opportunity = Opportunity::factory()->create(['organization_id' => $org->id, 'status' => 'open']);

        $volunteer = User::factory()->create(['role' => 'volunteer']);
        Application::create([
            'opportunity_id' => $opportunity->id,
            'user_id' => $volunteer->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($volunteer)->post("/opportunities/{$opportunity->id}/apply", [
            'message' => 'محاولة ثانية',
        ]);

        $response->assertStatus(422);
    }

    public function test_volunteer_can_cancel_pending_application(): void
    {
        $volunteer = User::factory()->create(['role' => 'volunteer']);
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create(['user_id' => $orgUser->id, 'name' => 'جمعية الأمل', 'city' => 'غزة']);
        $opportunity = Opportunity::factory()->create(['organization_id' => $org->id]);

        $application = Application::create([
            'opportunity_id' => $opportunity->id,
            'user_id' => $volunteer->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($volunteer)->post("/volunteer/applications/{$application->id}/cancel");

        $response->assertStatus(302);
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_organization_can_accept_and_reject_application(): void
    {
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create(['user_id' => $orgUser->id, 'name' => 'جمعية الأمل', 'city' => 'غزة']);
        $opportunity = Opportunity::factory()->create(['organization_id' => $org->id]);

        $volunteer = User::factory()->create(['role' => 'volunteer']);
        $application = Application::create([
            'opportunity_id' => $opportunity->id,
            'user_id' => $volunteer->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($orgUser)->post("/organization/applications/{$application->id}/respond", [
            'status' => 'accepted',
            'reviewer_note' => 'أهلاً بك معنا!',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'accepted',
            'reviewer_note' => 'أهلاً بك معنا!',
        ]);

        // Volunteer received accepted notification
        $this->assertDatabaseHas('notifications', [
            'user_id' => $volunteer->id,
            'type' => 'application_response',
        ]);
    }
}
