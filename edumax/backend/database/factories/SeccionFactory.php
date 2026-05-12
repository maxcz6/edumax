<?php

namespace Database\Factories;

use App\Models\Seccion;
use App\Models\Grado;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeccionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'grado_id' => Grado::factory(),
            'nombre' => fake()->unique()->randomElement(['A', 'B', 'C', 'D', 'E']),
            'aula' => fake()->regexify('[0-9]{3}'),
        ];
    }
}
