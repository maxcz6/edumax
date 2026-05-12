<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Institucion;
use App\Models\Docente;
use App\Models\Grado;
use Illuminate\Database\Eloquent\Factories\Factory;

class CursoFactory extends Factory
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
            'docente_id' => Docente::factory(),
            'grado_id' => Grado::factory(),
            'nombre' => fake()->randomElement(['Matemáticas', 'Lenguaje', 'Ciencias', 'Historia', 'Inglés', 'Educación Física', 'Arte']),
            'descripcion' => fake()->sentence(),
            'estado' => 'activo',
        ];
    }

    /**
     * Curso inactivo
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'inactivo',
        ]);
    }
}
