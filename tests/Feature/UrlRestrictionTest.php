<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlRestrictionTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_see_all_short_urls()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $company = Company::factory()->create();
        ShortUrl::factory()->create(['company_id' => $company->id, 'code' => 'CO_URL']);

        $response = $this->actingAs($superAdmin)->get('/short-urls');

        $response->assertStatus(200);
        $response->assertSee('CO_URL');
    }

    public function test_admin_can_only_see_urls_in_their_company()
    {
        $companyA = Company::factory()->create(['name' => 'Company A']);
        $companyB = Company::factory()->create(['name' => 'Company B']);
        
        $adminA = User::factory()->create(['role' => User::ROLE_ADMIN, 'company_id' => $companyA->id]);
        
        ShortUrl::factory()->create(['company_id' => $companyA->id, 'code' => 'URL_A']);
        ShortUrl::factory()->create(['company_id' => $companyB->id, 'code' => 'URL_B']);

        $response = $this->actingAs($adminA)->get('/short-urls');

        $response->assertStatus(200);
        $response->assertSee('URL_A');
        $response->assertDontSee('URL_B');
    }

    public function test_member_can_only_see_urls_created_by_themselves()
    {
        $company = Company::factory()->create();
        $member = User::factory()->create(['role' => User::ROLE_MEMBER, 'company_id' => $company->id]);
        $otherMember = User::factory()->create(['role' => User::ROLE_MEMBER, 'company_id' => $company->id]);

        ShortUrl::factory()->create(['user_id' => $member->id, 'code' => 'MY_URL', 'company_id' => $company->id]);
        ShortUrl::factory()->create(['user_id' => $otherMember->id, 'code' => 'OTHER_URL', 'company_id' => $company->id]);

        $response = $this->actingAs($member)->get('/short-urls');

        $response->assertStatus(200);
        $response->assertSee('MY_URL');
        $response->assertDontSee('OTHER_URL');
    }

    public function test_superadmin_cannot_create_urls()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        
        $response = $this->actingAs($superAdmin)->post('/short-urls', [
            'original_url' => 'https://google.com',
            'code' => 'no-sa'
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_and_member_can_create_urls()
    {
        $roles = [User::ROLE_ADMIN, User::ROLE_MEMBER];

        foreach ($roles as $role) {
            $user = User::factory()->create(['role' => $role]);
            
            $response = $this->actingAs($user)->post('/short-urls', [
                'original_url' => 'https://google.com',
                'code' => 'test-' . $role
            ]);

            $response->assertStatus(302);
            $this->assertDatabaseHas('short_urls', ['code' => 'test-' . $role]);
        }
    }

    public function test_url_resolution_is_public()
    {
        $url = ShortUrl::factory()->create(['code' => 'PUBLIC', 'original_url' => 'https://google.com']);

        $response = $this->get('/PUBLIC');

        $response->assertRedirect('https://google.com');
    }
}
