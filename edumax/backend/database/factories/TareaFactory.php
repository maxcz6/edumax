<?php

namespace Database\Factories;

use App\Models\Tarea;
use App\Models\Curso;
use Illuminate\Database\Eloquent\Factories\Factory;

class TareaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'curso_id' => Curso::factory(),
            'titulo' => fake()->sentence(),
            'descripcion' => fake()->paragraph(),
            'archivo' => null,
            'fecha_publicacion' => fake()->dateTime(),
            'fecha_entrega' => fake()->dateTimeBetween('+1 days', '+30 days'),
            'estado' => 'activo',
        ];
    }

    /**
     * Tarea cerrada
     */
    public function cerrada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'cerrado',
        ]);
    }
}
