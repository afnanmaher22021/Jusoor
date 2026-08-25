<?php

namespace Tests\Feature;

use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileAndAdminEnhancementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_volunteer_can_update_profile(): void
    {
        $volunteer = User::factory()->create([
            'role' => 'volunteer',
            'name' => 'اسم قديم',
            'city' => 'القدس',
        ]);

        $response = $this->actingAs($volunteer)->put('/profile', [
            'name' => 'اسم جديد',
            'email' => $volunteer->email,
            'city' => 'غزة',
            'phone' => '0599123456',
            'skills' => 'إدارة مشاريع، ترجمة',
            'monthly_hours_goal' => 25,
            'bio' => 'متطوع شغوف بالتعليم',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $volunteer->id,
            'name' => 'اسم جديد',
            'city' => 'غزة',
            'monthly_hours_goal' => 25,
        ]);
    }

    public function test_volunteer_can_update_password(): void
    {
        $volunteer = User::factory()->create([
            'role' => 'volunteer',
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->actingAs($volunteer)->put('/profile/password', [
            'current_password' => 'OldPassword123!',
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(Hash::check('NewSecurePassword123!', $volunteer->fresh()->password));
    }

    public function test_organization_can_update_profile_and_organization_details(): void
    {
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create([
            'user_id' => $orgUser->id,
            'name' => 'اسم المؤسسة القديم',
            'city' => 'القدس',
        ]);

        $response = $this->actingAs($orgUser)->put('/profile', [
            'name' => 'المدير الجديد',
            'email' => $orgUser->email,
            'city' => 'رام الله',
            'organization_name' => 'جمعية النهضة المجتمعية',
            'organization_description' => 'وصف المؤسسة الجديد',
            'website' => 'https://nahda.org',
            'founded_year' => '2019',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('organizations', [
            'id' => $org->id,
            'name' => 'جمعية النهضة المجتمعية',
            'city' => 'رام الله',
            'website' => 'https://nahda.org',
        ]);
    }

    public function test_volunteer_can_view_official_certificate(): void
    {
        $volunteer = User::factory()->create(['role' => 'volunteer', 'name' => 'خالد المتطوع']);
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create(['user_id' => $orgUser->id, 'name' => 'جمعية العطاء', 'city' => 'غزة']);
        $opp = Opportunity::factory()->create(['organization_id' => $org->id, 'title' => 'مبادرة التعليم']);

        Participation::create([
            'user_id' => $volunteer->id,
            'opportunity_id' => $opp->id,
            'hours' => 12.5,
            'work_date' => now()->format('Y-m-d'),
            'status' => 'approved',
        ]);

        $response = $this->actingAs($volunteer)->get('/volunteer/certificate');

        $response->assertStatus(200);
        $response->assertSee('خالد المتطوع');
        $response->assertSee('شهادة خبرة وتطوع معتمدة');
        $response->assertSee('12.5');
        $response->assertSee('JSR-' . date('Y'));
    }

    public function test_admin_can_toggle_organization_verification(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $orgUser = User::factory()->create(['role' => 'organization']);
        $org = Organization::create([
            'user_id' => $orgUser->id,
            'name' => 'مؤسسة غير موثقة',
            'city' => 'غزة',
            'verified' => false,
        ]);

        $response = $this->actingAs($admin)->post("/admin/organizations/{$org->id}/verify");

        $response->assertStatus(302);
        $this->assertTrue($org->fresh()->verified);

        // Toggle back to unverified
        $this->actingAs($admin)->post("/admin/organizations/{$org->id}/verify");
        $this->assertFalse($org->fresh()->verified);
    }
}
