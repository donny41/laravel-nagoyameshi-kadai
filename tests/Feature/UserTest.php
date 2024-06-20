<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_guest_can_access_user_index()
    {
        //未ログインユーザーが管理画面のユーザー一覧へアクセスしようとする
        $response = $this->get(route('user.index'));

        // 失敗することを検証
        $response->assertRedirect('/login');
    }

    public function test_user_can_access_user_index()
    {
        // ユーザーを作成
        $user = User::factory()->create();

        // ログイン操作を明示的にしたいときはこれ
        // $response = $this->post('/login', [
        //     'email' => $user->email,
        //     'password' => $user->password,
        // ]);

        // ユーザーとしてログインした状態（actingAs）
        $response = $this->actingAs($user, 'web')->get(route('user.index'));
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_user_index()
    {
        // 管理者を作成
        $admin = Admin::factory()->create();

        // 明示的にログインしたいとき
        // $this->post('/admin/login', [
        //     'email' => $admin->email,
        //     'password' => 'password',
        // ]);

        // 管理者としてログイン済として、テスト
        $response = $this->actingAs($admin, 'admin')->get(route('user.index'));
        $response->assertRedirect('/login');
        // $response->assertRedirect(route('admin.home'));

    }

    // edit 4つ
    // edit
    public function test_guest_cannot_edit_user()
    {
        $user = User::factory()->create();
        $response = $this->get(route("user.edit", $user));
        $response->assertRedirect('/login');
    }
    public function test_user_cannot_edit_other_user()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('user.edit', $user2));
        $response->assertRedirect(route('user.index'));
    }

    public function test_user_can_edit_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('user.edit', $user));
        $response->assertStatus(200);
    }
    public function test_admin_cannot_edit_user()
    {
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('user.edit', $admin));
        $response->assertRedirect('/login');
    }

    // update 
    public function test_guest_cannot_update_user()
    {
        $old_user = User::factory()->create([
            'name' => 'before',
        ]);
        $new_user_data = [
            'name' => 'after',
            'kana' => 'ア',
            'email' => 'aiu@email.com',
            'postal_code' => '1234567',
            'address' => 'あ',
            'phone_number' => '09011111111',
            'birthday' => '19001010',
            'occupation' => 'あ',
        ];
        $response = $this->patch(route('user.update', $old_user), $new_user_data);

        // DBが更新されたかチェック
        // $this->assertEquals(User::find($old_user->id)->name, $new_user_data['name']);
        $this->assertNotEquals(User::find($old_user->id)->name, $new_user_data['name']);

        // $response->assertRedirect(route('user.index'));
        $response->assertRedirect('/login');

    }
    public function test_user_cannot_update_other_user()
    {
        $other_user = User::factory()->create([
            'name' => 'other',
        ]);
        $old_user = User::factory()->create([
            'name' => 'before',
        ]);

        // print ('==========================================================================================' . "\n");
        // print ('=== Userテーブル    ' . "\n");
        // print ('==========================================================================================' . "\n");
        // print_r(User::all()->toArray());

        $new_user_data = [
            'name' => 'after',
            'kana' => 'ア',
            'email' => 'aiu@email.com',
            'postal_code' => '1234567',
            'address' => 'あ',
            'phone_number' => '09011111111',
            'birthday' => '19001010',
            'occupation' => 'あ',
        ];
        $response = $this->actingAs($other_user, 'web')->patch(route('user.update', $old_user), $new_user_data);

        // print ('==========================================================================================' . "\n");
        // print ('=== update後のUserテーブル    ' . "\n");
        // print ('==========================================================================================' . "\n");
        // print_r(User::all()->toArray());


        // DBが更新されたかチェック
        // $this->assertEquals(User::find($old_user->id)->name, $new_user_data['name']);
        $this->assertNotEquals(User::find($old_user->id)->name, $new_user_data['name']);

        $response->assertRedirect(route('user.index'));
        // $response->assertRedirect('/login');

    }
    public function test_user_can_update__user()
    {
        $old_user = User::factory()->create([
            'name' => 'before',
        ]);
        $new_user_data = [
            'name' => 'after',
            'kana' => 'ア',
            'email' => 'aiu@email.com',
            'postal_code' => '1234567',
            'address' => 'あ',
            'phone_number' => '09011111111',
            'birthday' => '19001010',
            'occupation' => 'あ',
        ];
        $response = $this->actingAs($old_user, 'web')->patch(route('user.update', $old_user), $new_user_data);

        // DBが更新されたかチェック
        $this->assertEquals(User::find($old_user->id)->name, $new_user_data['name']);
        // $this->assertNotEquals(User::find($old_user_id)->name, $new_user_data['name']);

        $response->assertRedirect(route('user.index'));
        // $response->assertRedirect('/login');

    }
    public function test_admin_cannot_update_company()
    {
        $admin = Admin::factory()->create();
        $old_user = User::factory()->create([
            'name' => 'before',
        ]);

        // print ('==========================================================================================' . "\n");
        // print ('=== Userテーブル    ' . "\n");
        // print ('==========================================================================================' . "\n");
        // print_r(User::all()->toArray());

        $new_user_data = [
            'name' => 'after',
            'kana' => 'ア',
            'email' => 'aiu@email.com',
            'postal_code' => '1234567',
            'address' => 'あ',
            'phone_number' => '09011111111',
            'birthday' => '19001010',
            'occupation' => 'あ',
        ];
        $response = $this->actingAs($admin, 'admin')->patch(route('user.update', $old_user), $new_user_data);

        // print ('==========================================================================================' . "\n");
        // print ('=== update後のUserテーブル    ' . "\n");
        // print ('==========================================================================================' . "\n");
        // print_r(User::all()->toArray());


        // DBが更新されたかチェック
        // 更新された
        // $this->assertEquals(User::find($old_user->id)->name, $new_user_data['name']);
        // 更新されなかった
        $this->assertNotEquals(User::find($old_user->id)->name, $new_user_data['name']);

        // $response->assertRedirect(route('user.index'));
        $response->assertRedirect('/login');
    }
}
