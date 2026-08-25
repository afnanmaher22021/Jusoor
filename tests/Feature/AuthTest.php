<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('تسجيل الدخول');
    }

    public function test_user_can_view_register_page(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('إنشاء حساب جديد');
    }

    public function test_volunteer_can_register(): void
    {
        $response = $this->post('/register', [
            'role' => 'volunteer',
            'name' => 'متطوع تجريبي',
            'email' => 'volunteer_test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'city' => 'غزة',
            'phone' => '0599111222',
        ]);

        $response->assertRedirect('/volunteer/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'volunteer_test@example.com',
            'role' => 'volunteer',
        ]);
    }

    public function test_organization_can_register(): void
    {
        $response = $this->post('/register', [
            'role' => 'organization',
            'name' => 'مسؤول المؤسسة',
            'email' => 'org_test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'city' => 'القدس',
            'organization_name' => 'جمعية العطاء',
            'organization_description' => 'جمعية خيرية',
        ]);

        $response->assertRedirect('/organization/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'org_test@example.com',
            'role' => 'organization',
        ]);
        $this->assertDatabaseHas('organizations', [
            'name' => 'جمعية العطاء',
        ]);
    }

    public function test_user_cannot_register_as_admin(): void
    {
        $response = $this->post('/register', [
            'role' => 'admin',
            'name' => 'هاكر',
            'email' => 'hacker@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'city' => 'غزة',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertGuest();
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'valid@example.com',
            'password' => bcrypt('Secret123!'),
            'role' => 'volunteer',
        ]);

        $response = $this->post('/login', [
            'email' => 'valid@example.com',
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect('/volunteer/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'valid@example.com',
            'password' => bcrypt('Secret123!'),
        ]);

        $response = $this->post('/login', [
            'email' => 'valid@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create(['role' => 'volunteer']);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
