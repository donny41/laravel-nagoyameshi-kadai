<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_create(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('subscription.create'));
        $response->assertStatus(200);

    }

    public function test_store(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('subscription.create'));

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];

        $response = $this->actingAs($user, 'web')->post(route('subscription.store', $request_parameter));

        $this->assertTrue(User::find($user->id)->subscribed('premium_plan'));
    }

    public function test_edit(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('subscription.create'));

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->actingAs(User::find($user->id), 'web')->post(route('subscription.store', $request_parameter));

        $response = $this->actingAs(User::find($user->id), 'web')->get(route('subscription.edit'));

        $response->assertStatus(200);
    }

    public function test_update(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('subscription.create'));

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->actingAs(User::find($user->id), 'web')->post(route('subscription.store', $request_parameter));

        $default_payment_method_id_1 = User::find($user->id)->defaultPaymentMethod()->id;

        $response = $this->actingAs(User::find($user->id), 'web')->get(route('subscription.edit'));

        $request_parameter = [
            'paymentMethodId' => 'pm_card_mastercard'
        ];

        $response = $this->actingAs(User::find($user->id), 'web')->patch(route('subscription.update',$request_parameter ));

        $default_payment_method_id_2 = User::find($user->id)->defaultPaymentMethod()->id;

        $this->assertNotEquals($default_payment_method_id_1, $default_payment_method_id_2);
    }

    public function test_cancel(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'web')->get(route('subscription.create'));

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->actingAs(User::find($user->id), 'web')->post(route('subscription.store', $request_parameter));

        $response = $this->actingAs(User::find($user->id), 'web')->get(route('subscription.cancel'));

        $response->assertStatus(200);
    }

    public function test_destroy(): void
    {
        $user = User::factory()->create();

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->actingAs(User::find($user->id), 'web')->post(route('subscription.store', $request_parameter));

        $response = $this->actingAs(User::find($user->id), 'web')->delete(route('subscription.destroy'));

        $this->assertFalse(User::find($user->id)->subscribed('premium_plan'));
    }

}
