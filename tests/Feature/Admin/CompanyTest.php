<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Company;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    // index edit update
    public function test_guest_cannot_access_company_index()
    {
        $company = Company::factory()->create();
        $response = $this->get(route("admin.company.index"));
        $response->assertRedirect(route("admin.login"));
    }
    public function test_user_cannot_access_company_index()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('admin.company.index'));
        $response->assertRedirect(route("admin.login"));
    }
    public function test_admin_can_access_company_index()
    {
        $company = Company::factory()->create();
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.company.index'));
        $response->assertStatus(200);
    }

    // edit
    public function test_guest_cannot_edit_company()
    {
        $company = Company::factory()->create();
        $response = $this->get(route("admin.company.edit", $company));
        $response->assertRedirect(route("admin.login"));
    }
    public function test_user_cannot_edit_company()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('admin.company.edit', $company));
        $response->assertRedirect(route("admin.login"));
    }
    public function test_admin_can_edit_company()
    {
        $admin = Admin::factory()->create();
        $company = Company::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.company.edit', $company));
        $response->assertStatus(200);
    }

    // update 
    public function test_guest_cannot_update_company()
    {
        $old_company = Company::factory()->create();
        $new_company = [
            'name' => 'あ',
            'postal_code' => '1234567',
            'address' => 'あ',
            'representative' => 'あ',
            'establishment_date' => 'あ',
            'capital' => 'あ',
            'business' => 'あ',
            'number_of_employees' => 'あ',
        ];
        $response = $this->patch(route('admin.company.update', $old_company), $new_company);
        $this->assertDatabaseMissing('companies', $new_company);
        $response->assertRedirect(route('admin.login'));
    }
    public function test_user_cannot_update_company()
    {
        $user = User::factory()->create();
        $old_company = Company::factory()->create();
        $new_company = [
            'name' => 'あ',
            'postal_code' => '1234567',
            'address' => 'あ',
            'representative' => 'あ',
            'establishment_date' => 'あ',
            'capital' => 'あ',
            'business' => 'あ',
            'number_of_employees' => 'あ',
        ];
        $response = $this->actingAs($user, 'web')->patch(route('admin.company.update', $old_company), $new_company);
        $this->assertDatabaseMissing('companies', $new_company);
        $response->assertRedirect(route('admin.login'));
    }
    public function test_admin_can_update_company()
    {
        $admin = Admin::factory()->create();
        $old_company = Company::factory()->create();
        $new_company = [
            'name' => 'あ',
            'postal_code' => '1234567',
            'address' => 'あ',
            'representative' => 'あ',
            'establishment_date' => 'あ',
            'capital' => 'あ',
            'business' => 'あ',
            'number_of_employees' => 'あ',
        ];
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.company.update', $old_company), $new_company);

        $this->assertDatabaseHas('companies', $new_company);

        $response->assertRedirect(route('admin.company.index'));
    }

}
