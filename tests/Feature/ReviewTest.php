<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Review;

class ReviewTest extends TestCase
{

    use RefreshDatabase;

    public function test_index()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $review = Review::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $this->actingAs(User::find($user->id), 'web')->get(route("restaurants.reviews.index",$restaurant))->assertStatus(200);
    }
    public function test_main()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        $review = Review::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->actingAs(User::find($user->id), 'web')->post(route('subscription.store', $request_parameter));

        $this->actingAs(User::find($user->id), 'web')->get(route("restaurants.reviews.edit",[$restaurant, $review]))->assertStatus(200);

        
    }

}
