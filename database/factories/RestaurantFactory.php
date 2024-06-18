<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->realText(10),
            // 'image' => fake()->name(),
            'description' => fake()->realText(50),
            'lowest_price' => fake()->numberBetween(500,1000),
            'highest_price' => fake()->numberBetween(2000,10000),
            'postal_code' => fake()->randomNumber(7,true),
            'address' => fake()->city(),
            'opening_time' => '10:00:00',
            'closing_time' => '19:00:00',
            'seating_capacity' => fake()->randomNumber(3,false),
        ];
    }
}
