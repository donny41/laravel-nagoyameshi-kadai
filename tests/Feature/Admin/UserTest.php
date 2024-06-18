<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /*
    会員一覧ページ
    未ログインのユーザーは管理者側の会員一覧ページにアクセスできない
    ログイン済みの一般ユーザーは管理者側の会員一覧ページにアクセスできない
    ログイン済みの管理者は管理者側の会員一覧ページにアクセスできる
    */
    public function test_gest_cannot_access_user_index()
    {
        //未ログインユーザーが管理画面のユーザー一覧へアクセスしようとする
        $response = $this->get(route("admin.users.index"));

        // 失敗することを検証
        $response->assertRedirect(route("admin.login"));
    }

    public function test_user_cannot_access_user_index()
    {
        // ユーザーを作成
        $user = User::factory()->create();

        // ログイン操作を明示的にしたいときはこれ
        // $response = $this->post('/login', [
        //     'email' => $user->email,
        //     'password' => $user->password,
        // ]);

        // ユーザーとしてログインした状態（actingAs）
        $response = $this->actingAs($user, 'web')->get(route('admin.users.index'));

        // 失敗すれば passed
        $response->assertRedirect(route("admin.login"));

    }

    public function test_admin_can_access_user_index()
    {
        // 管理者を作成
        $admin = Admin::factory()->create();

        // 明示的にログインしたいとき
        // $this->post('/admin/login', [
        //     'email' => $admin->email,
        //     'password' => 'password',
        // ]);

        // 管理者としてログイン済として、テスト
        $response = $this->actingAs($admin, 'admin')->get(route('admin.users.index'));

        // 正常に遷移すれば passed
        $response->assertStatus(200);
    }


    /*
    会員詳細ページ
    未ログインのユーザーは管理者側の会員詳細ページにアクセスできない
    ログイン済みの一般ユーザーは管理者側の会員詳細ページにアクセスできない
    ログイン済みの管理者は管理者側の会員詳細ページにアクセスできる
    */
    public function test_gest_cannot_access_user_show()
    {
        $user = User::factory()->create();

        //未ログインユーザーが管理画面のユーザー一覧へアクセスしようとする
        $response = $this->get(route("admin.users.show", $user));

        // 失敗することを検証
        $response->assertRedirect(route("admin.login"));
    }

    public function test_user_cannot_access_user_show()
    {
        // ユーザーを作成
        $user = User::factory()->create();

        // ログイン操作を明示的にしたいときはこれ
        // $response = $this->post('/login', [
        //     'email' => $user->email,
        //     'password' => $user->password,
        // ]);

        // ユーザーとしてログインした状態（actingAs）
        $response = $this->actingAs($user, 'web')->get(route('admin.users.show', $user));

        // 失敗すれば passed
        $response->assertRedirect(route("admin.login"));

    }

    public function test_admin_can_access_user_show()
    {
        // 管理者を作成
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        // 明示的にログインしたいとき
        // $this->post('/admin/login', [
        //     'email' => $admin->email,
        //     'password' => 'password',
        // ]);

        // 管理者としてログイン済として、テスト
        $response = $this->actingAs($admin, 'admin')->get(route('admin.users.show', $user));

        // 正常に遷移すれば passed
        $response->assertStatus(200);
    }



}
