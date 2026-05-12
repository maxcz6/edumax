<?php

namespace Database\Factories;

use App\Models\Estudiante;
use App\Models\User;
use App\Models\Padre;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstudianteFactory extends Factory
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
            'padre_id' => Padre::factory(),
            'codigo_estudiante' => fake()->unique()->regexify('[A-Z]{3}[0-9]{6}'),
            'dni' => fake()->unique()->regexify('[0-9]{8}'),
            'fecha_nacimiento' => fake()->dateTimeBetween('-18 years', '-5 years'),
            'genero' => fake()->randomElement(['M', 'F', 'Otro']),
            'direccion' => fake()->address(),
            'estado' => 'activo',
        ];
    }

    /**
     * Estudiante retirado
     */
    public function retirado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'retirado',
        ]);
    }

    /**
     * Estudiante egresado
     */
    public function egresado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'egresado',
        ]);
    }
}
