<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationRestrictionTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_invite_admin_in_a_new_company()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $company = Company::factory()->create();

        $response = $this->actingAs($superAdmin)->post('/invite', [
            'name' => 'New Admin',
            'email' => 'newadmin@example.com',
            'role' => User::ROLE_ADMIN,
            'company_id' => $company->id,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'newadmin@example.com', 'role' => User::ROLE_ADMIN, 'company_id' => $company->id]);
    }

    public function test_superadmin_cannot_invite_member()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $company = Company::factory()->create();

        $response = $this->actingAs($superAdmin)->post('/invite', [
            'name' => 'New Member',
            'email' => 'newmember@example.com',
            'role' => User::ROLE_MEMBER,
            'company_id' => $company->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_invite_admin_or_member_in_their_own_company()
    {
        $company = Company::factory()->create();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'company_id' => $company->id]);

        // Invite Admin
        $response1 = $this->actingAs($admin)->post('/invite', [
            'name' => 'Another Admin',
            'email' => 'admin2@example.com',
            'role' => User::ROLE_ADMIN,
        ]);
        $response1->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'admin2@example.com', 'role' => User::ROLE_ADMIN, 'company_id' => $company->id]);

        // Invite Member
        $response2 = $this->actingAs($admin)->post('/invite', [
            'name' => 'A Member',
            'email' => 'member@example.com',
            'role' => User::ROLE_MEMBER,
        ]);
        $response2->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'member@example.com', 'role' => User::ROLE_MEMBER, 'company_id' => $company->id]);
    }

    public function test_member_cannot_invite()
    {
        $member = User::factory()->create(['role' => User::ROLE_MEMBER]);

        $response = $this->actingAs($member)->post('/invite', [
            'name' => 'Should Fail',
            'email' => 'fail@example.com',
            'role' => User::ROLE_MEMBER,
        ]);

        $response->assertStatus(403);
    }
}
