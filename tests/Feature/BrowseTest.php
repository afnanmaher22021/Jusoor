<?php

namespace Tests\Feature;

use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    public function test_browse_page_renders_successfully_with_pagination(): void
    {
        $orgUser = User::factory()->create(['role' => 'organization']);
        $organization = Organization::create([
            'user_id' => $orgUser->id,
            'name' => 'Test Org',
            'city' => 'Gaza',
        ]);

        Opportunity::factory()->count(15)->create([
            'organization_id' => $organization->id,
            'status' => 'open',
        ]);

        $response = $this->get('/browse');

        $response->assertStatus(200);
        $response->assertSee('استكشف الفرص التطوعية');
        $response->assertSee('السابق');
        $response->assertSee('التالي');
    }
}
