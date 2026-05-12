<?php

namespace Database\Factories;

use App\Models\EntregaTarea;
use App\Models\Tarea;
use App\Models\Estudiante;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntregaTareaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tarea_id' => Tarea::factory(),
            'estudiante_id' => Estudiante::factory(),
            'archivo' => null,
            'comentario' => fake()->sentence(),
            'fecha_entrega' => fake()->dateTime(),
            'calificado' => false,
        ];
    }

    /**
     * Entrega calificada
     */
    public function calificada(): static
    {
        return $this->state(fn (array $attributes) => [
            'calificado' => true,
        ]);
    }
}
