<?php

namespace Database\Factories;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocenteFactory extends Factory
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
            'especialidad' => fake()->word(),
            'grado_academico' => fake()->randomElement(['Licenciatura', 'Maestría', 'Doctorado']),
            'fecha_contratacion' => fake()->dateTime(),
        ];
    }
}
