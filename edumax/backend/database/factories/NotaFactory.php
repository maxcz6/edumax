<?php

namespace Database\Factories;

use App\Models\Nota;
use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\Tarea;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotaFactory extends Factory
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
            'tarea_id' => Tarea::factory(),
            'nota' => fake()->randomFloat(2, 0, 20),
            'observacion' => fake()->sentence(),
            'fecha_registro' => fake()->dateTime(),
        ];
    }
}
