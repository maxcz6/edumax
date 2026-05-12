<?php

namespace Database\Factories;

use App\Models\Padre;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PadreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'ocupacion' => fake()->jobTitle(),
            'parentesco' => fake()->randomElement(['Padre', 'Madre', 'Abuelo', 'Tío']),
        ];
    }
}
