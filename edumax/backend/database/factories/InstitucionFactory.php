<?php

namespace Database\Factories;

use App\Models\Institucion;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstitucionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->company(),
            'codigo_modular' => fake()->unique()->regexify('[0-9]{6}'),
            'direccion' => fake()->address(),
            'telefono' => fake()->phoneNumber(),
            'correo' => fake()->unique()->companyEmail(),
            'logo' => null,
            'estado' => 'activo',
        ];
    }

    /**
     * Institución inactiva
     */
    public function inactiva(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'inactivo',
        ]);
    }
}
