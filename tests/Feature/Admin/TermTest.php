<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Term;

class TermTest extends TestCase
{
    use RefreshDatabase;

    // index edit update
    public function test_guest_cannot_access_term_index()
    {
        $term = Term::factory()->create();
        $response = $this->get(route("admin.terms.index"));
        $response->assertRedirect(route("admin.login"));
    }
    public function test_user_cannot_access_term_index()
    {
        $term = Term::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('admin.terms.index'));
        $response->assertRedirect(route("admin.login"));
    }
    public function test_admin_can_access_term_index()
    {
        $term = Term::factory()->create();
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.terms.index'));
        $response->assertStatus(200);
    }

    // edit
    public function test_guest_cannot_edit_term()
    {
        $term = Term::factory()->create();
        $response = $this->get(route("admin.terms.edit", $term));
        $response->assertRedirect(route("admin.login"));
    }
    public function test_user_cannot_edit_term()
    {
        $user = User::factory()->create();
        $term = Term::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('admin.terms.edit', $term));
        $response->assertRedirect(route("admin.login"));
    }
    public function test_admin_can_edit_term()
    {
        $admin = Admin::factory()->create();
        $term = Term::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.terms.edit', $term));
        $response->assertStatus(200);
    }

    // update
    public function test_guest_cannot_update_term()
    {
        $old_term = Term::factory()->create();
        $new_term = [
            'content' => 'あ',
        ];
        $response = $this->patch(route('admin.terms.update', $old_term), $new_term);
        $this->assertDatabaseMissing('terms', $new_term);
        $response->assertRedirect(route('admin.login'));
    }
    public function test_user_cannot_update_term()
    {
        $user = User::factory()->create();
        $old_term = Term::factory()->create();
        $new_term = [
            'content' => 'あ',
        ];
        $response = $this->actingAs($user, 'web')->patch(route('admin.terms.update', $old_term), $new_term);
        $this->assertDatabaseMissing('terms', $new_term);
        $response->assertRedirect(route('admin.login'));
    }
    public function test_admin_can_update_term()
    {
        $admin = Admin::factory()->create();
        $old_term = Term::factory()->create();
        $new_term = [
            'content' => 'あ',
        ];
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.terms.update', $old_term), $new_term);

        $this->assertDatabaseHas('terms', $new_term);

        $response->assertRedirect(route('admin.terms.index'));
    }
}
