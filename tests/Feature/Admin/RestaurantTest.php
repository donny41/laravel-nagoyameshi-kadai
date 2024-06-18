<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    /*
    indexアクション（店舗詳細ページ） 未ログイン、ログイン済かつ非管理者、管理者
    showアクション（店舗詳細ページ） 未ログイン、ログイン済かつ非管理者、管理者
    createアクション（店舗詳細ページ） 未ログイン、ログイン済かつ非管理者、管理者
    storeアクション（店舗詳細ページ） 未ログイン、ログイン済かつ非管理者、管理者
    editアクション（店舗詳細ページ） 未ログイン、ログイン済かつ非管理者、管理者
    updateアクション（店舗詳細ページ） 未ログイン、ログイン済かつ非管理者、管理者
    destroyアクション（店舗詳細ページ） 未ログイン、ログイン済かつ非管理者、管理者
    */

    // index
    public function test_guest_cannot_access_restaurant_index()
    {
        $response = $this->get(route("admin.restaurants.index"));
        $response->assertRedirect(route("admin.login"));
    }

    public function test_user_cannot_access_restaurant_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('admin.restaurants.index'));
        $response->assertRedirect(route("admin.login"));
    }

    public function test_admin_can_access_restaurant_index()
    {
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.index'));
        $response->assertStatus(200);
    }

    // show
    public function test_guest_cannot_access_restaurant_show()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route("admin.restaurants.show", $restaurant));
        $response->assertRedirect(route("admin.login"));
    }

    public function test_user_cannot_access_restaurant_show()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('admin.restaurants.show', $restaurant));
        $response->assertRedirect(route("admin.login"));
    }

    public function test_admin_can_access_restaurant_show()
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.show', $restaurant));
        $response->assertStatus(200);
    }

    // create
    public function test_guest_cannot_access_restaurant_create()
    {
        $response = $this->get(route("admin.restaurants.create"));
        $response->assertRedirect(route("admin.login"));
    }

    public function test_user_cannot_access_restaurant_create()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('admin.restaurants.create'));
        $response->assertRedirect(route("admin.login"));
    }

    public function test_admin_can_access_restaurant_create()
    {
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.create'));
        $response->assertStatus(200);
    }

    // store
    public function test_guest_cannot_store_restaurant()
    {
        $restaurant = [
            'name' => 'あ',
            // 'image' => '',
            'description' => 'あ',
            'lowest_price' => '100',
            'highest_price' => '10000',
            'postal_code' => '1234567',
            'address' => '東京1',
            'opening_time' => '10:00;00',
            'closing_time' => '19:00:00',
            'seating_capacity' => '50',
        ];
        $response = $this->post(route('admin.restaurants.store', $restaurant));
        $this->assertDatabaseMissing('restaurants', $restaurant);
        $response->assertRedirect(route("admin.login"));
    }
    public function test_user_cannot_store_restaurant()
    {
        $user = User::factory()->create();
        $restaurant = [
            'name' => 'あ',
            // 'image' => '',
            'description' => 'あ',
            'lowest_price' => '100',
            'highest_price' => '10000',
            'postal_code' => '1234567',
            'address' => '東京1',
            'opening_time' => '10:00;00',
            'closing_time' => '19:00:00',
            'seating_capacity' => '50',
        ];
        $response = $this->actingAs($user, 'web')->post(route('admin.restaurants.store', $restaurant));
        $this->assertDatabaseMissing('restaurants', $restaurant);
        $response->assertRedirect(route("admin.login"));
    }
    public function test_admin_can_store_restaurant()
    {
        $admin = Admin::factory()->create();
        $restaurant = [
            'name' => 'あ',
            // 'image' => '',
            'description' => 'あ',
            'lowest_price' => '100',
            'highest_price' => '10000',
            'postal_code' => '1234567',
            'address' => '東京1',
            'opening_time' => '10:00:00',
            'closing_time' => '19:00:00',
            'seating_capacity' => '50',
        ];
        $response = $this->actingAs($admin, 'admin')->post(route('admin.restaurants.store', $restaurant));
        $this->assertDatabaseHas('restaurants', $restaurant);
        $response->assertRedirect(route('admin.restaurants.index'));
    }

    // edit
    public function test_guest_cannot_access_restaurant_edit()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route("admin.restaurants.edit", $restaurant));
        $response->assertRedirect(route("admin.login"));
    }
    public function test_user_cannot_access_restaurant_edit()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('admin.restaurants.edit', $restaurant));
        $response->assertRedirect(route("admin.login"));
    }

    public function test_admin_can_access_restaurant_edit()
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.edit', $restaurant));
        $response->assertStatus(200);
    }

    // update
    public function test_guest_cannot_update_restaurant()
    {
        $old_restaurant = Restaurant::factory()->create();
        $new_restaurant = [
            'name' => 'あ',
            // 'image' => '',
            'description' => 'あ',
            'lowest_price' => '100',
            'highest_price' => '10000',
            'postal_code' => '1234567',
            'address' => '東京1',
            'opening_time' => '10:00:00',
            'closing_time' => '19:00:00',
            'seating_capacity' => '50',
        ];
        $response = $this->patch(route('admin.restaurants.update', $old_restaurant), $new_restaurant);
        $this->assertDatabaseMissing('restaurants', $new_restaurant);
        $response->assertRedirect(route('admin.login'));
    }
    public function test_user_cannot_update_restaurant()
    {
        $user = User::factory()->create();
        $old_restaurant = Restaurant::factory()->create();
        $new_restaurant = [
            'name' => 'あ',
            // 'image' => '',
            'description' => 'あ',
            'lowest_price' => '100',
            'highest_price' => '10000',
            'postal_code' => '1234567',
            'address' => '東京1',
            'opening_time' => '10:00:00',
            'closing_time' => '19:00:00',
            'seating_capacity' => '50',
        ];
        $response = $this->actingAs($user, 'web')->patch(route('admin.restaurants.update', $old_restaurant), $new_restaurant);
        $this->assertDatabaseMissing('restaurants', $new_restaurant);
        $response->assertRedirect(route('admin.login'));
    }
    public function test_admin_can_update_restaurant()
    {
        $admin = Admin::factory()->create();
        $old_restaurant = Restaurant::factory()->create();
        $new_restaurant = [
            'name' => 'あ',
            // 'image' => '',
            'description' => 'あ',
            'lowest_price' => '100',
            'highest_price' => '10000',
            'postal_code' => '1234567',
            'address' => '東京1',
            'opening_time' => '10:00:00',
            'closing_time' => '19:00:00',
            'seating_capacity' => '50',
        ];
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.restaurants.update', $old_restaurant), $new_restaurant);
        $this->assertDatabaseHas('restaurants', $new_restaurant);
        $response->assertRedirect(route('admin.restaurants.index'));
    }


    // destroy
    public function test_guest_cannot_destroy_restaurant()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->delete(route("admin.restaurants.destroy", $restaurant));
        $this->assertModelExists($restaurant);
        $response->assertRedirect(route("admin.login"));
    }
    public function test_user_cannot_destroy_restaurant()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($user, 'web')->delete(route("admin.restaurants.destroy", $restaurant));
        $this->assertModelExists($restaurant);
        $response->assertRedirect(route("admin.login"));
    }
    public function test_admin_can_destroy_restaurant()
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $response = $this->actingAs($admin, 'admin')->delete(route("admin.restaurants.destroy", $restaurant));
        $this->assertModelMissing($restaurant);
        $response->assertRedirect(route('admin.restaurants.index'));
    }
}
