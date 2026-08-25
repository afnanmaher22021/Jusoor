<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAndNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_volunteer_can_access_volunteer_dashboard(): void
    {
        $volunteer = User::factory()->create(['role' => 'volunteer', 'monthly_hours_goal' => 20]);

        $response = $this->actingAs($volunteer)->get('/volunteer/dashboard');

        $response->assertStatus(200);
        $response->assertSee('لوحة تحكم المتطوع');
    }

    public function test_volunteer_cannot_access_organization_dashboard(): void
    {
        $volunteer = User::factory()->create(['role' => 'volunteer']);

        $response = $this->actingAs($volunteer)->get('/organization/dashboard');

        $response->assertStatus(403);
    }

    public function test_organization_can_access_organization_dashboard(): void
    {
        $orgUser = User::factory()->create(['role' => 'organization']);
        Organization::create(['user_id' => $orgUser->id, 'name' => 'مؤسسة الاختبار', 'city' => 'غزة']);

        $response = $this->actingAs($orgUser)->get('/organization/dashboard');

        $response->assertStatus(200);
        $response->assertSee('لوحة تحكم المؤسسة');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('لوحة تحكم الإدارة العامة');
    }

    public function test_user_can_view_and_mark_notifications_as_read(): void
    {
        $user = User::factory()->create(['role' => 'volunteer']);
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'hours',
            'title' => 'ساعات جديدة',
            'body' => 'تم تسجيل 3 ساعات لك.',
        ]);

        $response = $this->actingAs($user)->get('/notifications');
        $response->assertStatus(200);
        $response->assertSee('ساعات جديدة');

        $response = $this->actingAs($user)->post("/notifications/{$notification->id}/read");
        $response->assertStatus(302);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create(['role' => 'volunteer']);
        Notification::create([
            'user_id' => $user->id,
            'type' => 'hours',
            'title' => 'إشعار 1',
            'body' => 'تفاصيل 1',
        ]);
        Notification::create([
            'user_id' => $user->id,
            'type' => 'application_response',
            'title' => 'إشعار 2',
            'body' => 'تفاصيل 2',
        ]);

        $response = $this->actingAs($user)->post('/notifications/read-all');
        $response->assertStatus(302);

        $this->assertEquals(0, $user->notifications()->unread()->count());
    }
}
