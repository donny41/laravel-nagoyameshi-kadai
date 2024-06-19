<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
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
            'postal_code' => fake()->randomNumber(7, true),
            'address' => fake()->realText(10),
            'representative' => fake()->realText(10),
            'establishment_date' => fake()->realText(10),
            'capital' => fake()->realText(10),
            'business' => fake()->realText(10),
            'number_of_employees' => fake()->realText(10),
        ];
    }
}
