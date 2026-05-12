<?php

namespace Database\Factories;

use App\Models\Grado;
use App\Models\Institucion;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institucion_id' => Institucion::factory(),
            'nombre' => fake()->unique()->regexify('[0-9]{1,2}°'),
            'nivel' => fake()->randomElement(['Inicial', 'Primaria', 'Secundaria', 'Superior']),
        ];
    }
}
