<?php

namespace Database\Factories;

use App\Models\Matricula;
use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\Seccion;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatriculaFactory extends Factory
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
            'seccion_id' => Seccion::factory(),
            'anio_escolar' => fake()->year(),
            'fecha_matricula' => fake()->dateTime(),
            'estado' => 'activo',
        ];
    }

    /**
     * Matrícula retirada
     */
    public function retirada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'retirado',
        ]);
    }

    /**
     * Matrícula finalizada
     */
    public function finalizada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'finalizado',
        ]);
    }
}
