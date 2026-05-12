<?php

namespace Database\Factories;

use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Curso;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsistenciaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'estudiante_id' => Estudiante::factory(),
            'curso_id' => Curso::factory(),
            'fecha' => fake()->dateTime(),
            'estado' => fake()->randomElement(['presente', 'tardanza', 'falta', 'justificado']),
            'observacion' => fake()->sentence(),
        ];
    }
}
