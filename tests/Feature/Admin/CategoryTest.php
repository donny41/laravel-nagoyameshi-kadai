<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /*
    indexアクション 未ログイン、ログイン済かつ非管理者、管理者
    showアクション 未ログイン、ログイン済かつ非管理者、管理者
    createアクション 未ログイン、ログイン済かつ非管理者、管理者
    storeアクション 未ログイン、ログイン済かつ非管理者、管理者
    editアクション 未ログイン、ログイン済かつ非管理者、管理者
    updateアクション 未ログイン、ログイン済かつ非管理者、管理者
    destroyアクション 未ログイン、ログイン済かつ非管理者、管理者
    */

    // index
    public function test_guest_cannot_access_category_index()
    {
        $response = $this->get(route("admin.categories.index"));
        $response->assertRedirect(route("admin.login"));
    }

    public function test_user_cannot_access_category_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('admin.categories.index'));
        $response->assertRedirect(route("admin.login"));
    }

    public function test_admin_can_access_category_index()
    {
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.categories.index'));
        $response->assertStatus(200);
    }

    // show
    // public function test_guest_cannot_access_category_show()
    // {
    //     $category = Category::factory()->create();
    //     $response = $this->get(route("admin.categories.show", $category));
    //     $response->assertRedirect(route("admin.login"));
    // }

    // public function test_user_cannot_access_category_show()
    // {
    //     $user = User::factory()->create();
    //     $category = Category::factory()->create();
    //     $response = $this->actingAs($user, 'web')->get(route('admin.categories.show', $category));
    //     $response->assertRedirect(route("admin.login"));
    // }

    // public function test_admin_can_access_category_show()
    // {
    //     $admin = Admin::factory()->create();
    //     $category = Category::factory()->create();
    //     $response = $this->actingAs($admin, 'admin')->get(route('admin.categories.show', $category));
    //     $response->assertStatus(200);
    // }

    // create
    // public function test_guest_cannot_access_category_create()
    // {
    //     $response = $this->get(route("admin.categories.create"));
    //     $response->assertRedirect(route("admin.login"));
    // }

    // public function test_user_cannot_access_category_create()
    // {
    //     $user = User::factory()->create();
    //     $response = $this->actingAs($user, 'web')->get(route('admin.categories.create'));
    //     $response->assertRedirect(route("admin.login"));
    // }

    // public function test_admin_can_access_category_create()
    // {
    //     $admin = Admin::factory()->create();
    //     $response = $this->actingAs($admin, 'admin')->get(route('admin.categories.create'));
    //     $response->assertStatus(200);
    // }

    // store
    public function test_guest_cannot_store_category()
    {
        $category = [
            'name' => 'あ',
        ];
        $response = $this->post(route('admin.categories.store', $category));
        $this->assertDatabaseMissing('categories', $category);
        $response->assertRedirect(route("admin.login"));
    }
    public function test_user_cannot_store_category()
    {
        $user = User::factory()->create();
        $category = [
            'name' => 'あ',
        ];
        $response = $this->actingAs($user, 'web')->post(route('admin.categories.store', $category));
        $this->assertDatabaseMissing('categories', $category);
        $response->assertRedirect(route("admin.login"));
    }
    public function test_admin_can_store_category()
    {
        $admin = Admin::factory()->create();
        $category = [
            'name' => 'あ',
        ];
        $response = $this->actingAs($admin, 'admin')->post(route('admin.categories.store', $category));
        $this->assertDatabaseHas('categories', $category);
        $response->assertRedirect(route('admin.categories.index'));
    }

    // edit
    // public function test_guest_cannot_access_category_edit()
    // {
    //     $category = Category::factory()->create();
    //     $response = $this->get(route("admin.categories.edit", $category));
    //     $response->assertRedirect(route("admin.login"));
    // }
    // public function test_user_cannot_access_category_edit()
    // {
    //     $user = User::factory()->create();
    //     $category = Category::factory()->create();
    //     $response = $this->actingAs($user, 'web')->get(route('admin.categories.edit', $category));
    //     $response->assertRedirect(route("admin.login"));
    // }

    // public function test_admin_can_access_category_edit()
    // {
    //     $admin = Admin::factory()->create();
    //     $category = Category::factory()->create();
    //     $response = $this->actingAs($admin, 'admin')->get(route('admin.categories.edit', $category));
    //     $response->assertStatus(200);
    // }

    // update
    public function test_guest_cannot_update_category()
    {
        $old_category = Category::factory()->create();
        $new_category = [
            'name' => 'あ',
        ];
        $response = $this->patch(route('admin.categories.update', $old_category), $new_category);
        $this->assertDatabaseMissing('categories', $new_category);
        $response->assertRedirect(route('admin.login'));
    }
    public function test_user_cannot_update_category()
    {
        $user = User::factory()->create();
        $old_category = Category::factory()->create();
        $new_category = [
            'name' => 'あ',
        ];
        $response = $this->actingAs($user, 'web')->patch(route('admin.categories.update', $old_category), $new_category);
        $this->assertDatabaseMissing('categories', $new_category);
        $response->assertRedirect(route('admin.login'));
    }
    public function test_admin_can_update_category()
    {
        $admin = Admin::factory()->create();
        $old_category = Category::factory()->create();
        $new_category = [
            'name' => 'あ',
        ];
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.categories.update', $old_category), $new_category);
        $this->assertDatabaseHas('categories', $new_category);
        $response->assertRedirect(route('admin.categories.index'));
    }


    // destroy
    public function test_guest_cannot_destroy_category()
    {
        $category = Category::factory()->create();
        $response = $this->delete(route("admin.categories.destroy", $category));
        $this->assertModelExists($category);
        $response->assertRedirect(route("admin.login"));
    }
    public function test_user_cannot_destroy_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $response = $this->actingAs($user, 'web')->delete(route("admin.categories.destroy", $category));
        $this->assertModelExists($category);
        $response->assertRedirect(route("admin.login"));
    }
    public function test_admin_can_destroy_category()
    {
        $admin = Admin::factory()->create();
        $category = Category::factory()->create();
        $response = $this->actingAs($admin, 'admin')->delete(route("admin.categories.destroy", $category));
        $this->assertModelMissing($category);
        $response->assertRedirect(route('admin.categories.index'));
    }
}
