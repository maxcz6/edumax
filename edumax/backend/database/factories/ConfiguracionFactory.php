<?php

namespace Database\Factories;

use App\Models\Configuracion;
use App\Models\Institucion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConfiguracionFactory extends Factory
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
            'clave' => fake()->unique()->word(),
            'valor' => fake()->word(),
        ];
    }
}
