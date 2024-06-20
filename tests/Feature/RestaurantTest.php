<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_restaurant_index()
    {
        $response = $this->get(route('restaurants.index'));
        $response->assertStatus(200);
    }

    public function test_user_can_access_restaurant_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('restaurants.index'));
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_restaurant_index()
    {
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('restaurants.index'));
        $response->assertRedirect(route('admin.home'));
    }

    // show
    public function test_guest_can_access_restaurant_show()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.show',$restaurant));
        $response->assertStatus(200);
    }

    public function test_user_can_access_restaurant_show()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('restaurants.show',$restaurant));
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_restaurant_show()
    {
        $restaurant = Restaurant::factory()->create();
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('restaurants.show',$restaurant));
        $response->assertRedirect(route('admin.home'));
    }

}
