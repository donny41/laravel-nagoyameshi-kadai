<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    // index
    public function test_guest_can_access_home()
    {
        $restaurant = Restaurant::factory()->count(10)->create();
        $response = $this->get(route("home"));
        $response->assertStatus(200);
        // $response->assertRedirect(route("login"));
    }
    public function test_user_can_access_home()
    {
        $restaurant = Restaurant::factory()->count(10)->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('home'));
        $response->assertStatus(200);
        // $response->assertRedirect(route("admin.login"));
    }
    public function test_admin_cannot_access_home()
    {
        $restaurant = Restaurant::factory()->count(10)->create();
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('home'));
        $response->assertRedirect(route("admin.home"));
    }
}
